<?php

namespace App\Transformers;

use Illuminate\Pagination\UrlWindow;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer;

class CustomArraySerializer extends ArraySerializer
{
    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator):array
    {
        $currentPage = (int) $paginator->getCurrentPage();
        $lastPage = (int) $paginator->getLastPage();

        $from = (int) $paginator->getPerPage() * ($currentPage - 1) + 1;
        $pagination = [
            'from' => $from,
            'to' => (int) $from + $paginator->getCount() - 1,
            'total' => (int) $paginator->getTotal(),
            'count' => (int) $paginator->getCount(), // number of items in this page
            'per_page' => (int) $paginator->getPerPage(),
            'current_page' => $currentPage,
            'total_pages' => $lastPage,
        ];

        $pagination['links'] = [];

        $previous = (object) ['label' => 'Previous'];
        if ($currentPage > 1) {
            $previous = (object) ['label' => 'Previous', 'url' => $paginator->getUrl($currentPage - 1)];
        }

        $next = (object) ['label' => 'Next'];
        if ($currentPage < $lastPage) {
            $next = (object) ['label' => 'Next', 'url' => $paginator->getUrl($currentPage + 1)];
        }

        /** @phpstan-ignore-next-line */
        $pagination['links'] = array_merge([$previous], $this->elements($paginator->getPaginator()), [$next]);

        return ['pagination' => $pagination];
    }

    protected function elements($paginator)
    {
        $window = UrlWindow::make($paginator);

        $result = [];

        $currentPage = $paginator->currentPage();

        foreach ($window['first'] as $label => $url) {
            $result [] = $this->getPageData($label, $url, $currentPage);
        }

        if (is_array($window['slider'])) {
            $result [] = (object) ['label' => '...'];
            foreach ($window['slider'] as $label => $url) {
                $result [] = $this->getPageData($label, $url, $currentPage);
            }
        }

        if (is_array($window['last'])) {
            $result [] = (object) ['label' => '...'];
            foreach ($window['last'] as $label => $url) {
                $result [] = $this->getPageData($label, $url, $currentPage);
            }
        }

        return $result;
    }

    protected function getPageData($label, $url, $currentPage)
    {
        $pageData = ['label' => (string) $label, 'url' => $url];
        if ($currentPage === $label) {
            $pageData['active'] = true;
        }

        return (object) $pageData;
    }
}
