<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;

/**
 * Class GridHandler
 * @package Vemid\ProjectOne\Admin\Handler
 */
class GridHandler extends AbstractHandler
{
    /** @var array */
    protected $filterColumns = [];

    /** @var int */
    protected $offset = 0;

    /** @var int */
    protected $limit = 25;

    /** @var int */
    protected $page = 1;

    /** @var array  */
    protected $order = [];

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getParsedBody();
        foreach ($queryParams['columns'] as $key => $column) {
            if (!isset($column['searchable']) || !(bool)$column['searchable'] || !(bool)$column['search']['value']) {
                continue;
            }

            $this->filterColumns[$column['name']] = $column['search']['value'];

            foreach ($queryParams['order'] as $order) {
                if ((int)$order['column'] === $key) {
                    $this->order[$column['name']] = $order['dir'];
                }
            }

        }

        $this->offset = $queryParams['start'];
        $this->limit = (int)$queryParams['length'];
        $this->page = $queryParams['draw'] ?? 1;

        return parent::handle($request);
    }
}
