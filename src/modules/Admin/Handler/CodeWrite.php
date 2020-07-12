<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Helper\HtmlTag;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Misc\PhpToCryptoJs;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\CodeType;
use Vemid\ProjectOne\Entity\Repository\CodeRepository;

/**
 * Class CodeWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class CodeWrite extends GridHandler
{
    public function list(EntityManagerInterface $entityManager): array
    {
        /** @var CodeRepository $codeRepository */
        $codeRepository = $entityManager->getRepository(Code::class);
        $codes = $codeRepository->fetchCodes($this->limit, $this->offset, $this->filterColumns);
        $totalCodes = $codeRepository->fetchCodes(0, 0);
        $filteredCodes = $codeRepository->fetchCodes(0, 0, $this->filterColumns);

        $data = [];
        foreach ($codes as $code) {
            $data[] = [
                (string)$code->getCodeType(),
                (string)$code->getName(),
                (string)$code->getParent(),
                HtmlTag::groupLink([
                    HtmlTag::link('/codes/update/' . $code->getId(), false, 'text-default bigger-120', 'pencil-square-o', false),
                    HtmlTag::link('#', false, 'text-danger bigger-120', 'trash-o', false, [
                        'data-delete' => '',
                        'data-form-url' => htmlspecialchars(PhpToCryptoJs::cryptoJsAesEncrypt('Vemid', '/codes/delete/' . $code->getId())),
                        'data-title' => $this->translator->_('Obriši Kategoriju'),
                    ]),
                ])
            ];
        }

        return [
            'draw' => $this->page,
            'recordsTotal' => count($totalCodes),
            'recordsFiltered' => count($filteredCodes),
            'data' => $data
        ];
    }

    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $code = new Code();

        $form = $formBuilder->build($code);
        $postData = $form->getHttpData();
        $form->setValues($_POST);

        if (!$form->isValid()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$codeType = $entityManager->find(CodeType::class, $postData['codeType'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Tip ne postoji!'), null, Builder::DANGER);
            return;
        }

        $parent = null;
        if (!empty($postData['parent']) && !$parent = $entityManager->find(Code::class, $postData['parent'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podkategorija ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData['codeType'] = $codeType;
        $postData['parent'] = $parent;

        $code->setData($postData);

        $entityManager->persist($code);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Kategorija dodata!'), null, Builder::SUCCESS);

        return $this->redirect('/codes/list');
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
        /** @var $code Code */
        if (!$code = $entityManager->find(Code::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, nesto nije u redu. Ne postoji traženi dobavljač'), null, Builder::WARNING);
            return;
        }

        $form = $formBuilder->build($code);
        $postData = $form->getHttpData();

        if (!$form->isSuccess()) {
            $this->messageBag->pushFormValidationMessages($form);
            return;
        }

        if (!$codeType = $entityManager->find(CodeType::class, $postData['codeType'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Tip ne postoji!'), null, Builder::DANGER);
            return;
        }

        $parent = null;
        if (!empty($postData['parent']) && !$parent = $entityManager->find(Code::class, $postData['parent'])) {
            $this->messageBag->pushFlashMessage($this->translator->_('Podkategorija ne postoji!'), null, Builder::DANGER);
            return;
        }

        $postData['codeType'] = $codeType;
        $postData['parent'] = $parent;

        $code->setData($postData);

        $entityManager->persist($code);
        $entityManager->flush();

        $this->messageBag->pushFlashMessage($this->translator->_('Dobaljač izmenjen'), null, Builder::SUCCESS);

        $this->redirect('/codes/list');
    }
}
