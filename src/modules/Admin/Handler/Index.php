<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Vemid\ProjectOne\Common\Pdf\PdfBuilderInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\User;

/**
 * Class Index
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Index extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function index(EntityManagerInterface $entityManager): void
    {
        $user = $entityManager->find(User::class, $this->session->get('user')['id']);

        $this->view->setTemplate('index::index.html.twig', ['user' => $user]);
    }

    public function pdf(PdfBuilderInterface $pdf, ResponseInterface $response): ResponseInterface
    {
        $output = $pdf->render($this->template->render('pdf::test.html.twig'));

        $response->getBody()->write($output);
        $response->getBody()->rewind();

        header('Content-type: application/pdf');
        header('Content-disposition: attachment; filename=test.pdf');

        return $response;
    }
}
