<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath; // configuration dans services.yaml

    public function __construct(ManagerRegistry $managerRegistry, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->manager      = $managerRegistry->getManager();
        $this->twig         = $twig;
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }
    public function getData()
    {
        if(empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entity sur laquelle nous devons paginer.
             Utiliser la méthode setEntityClass() de votre objet PaginationService !");
        }
        // calcul de l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        //Demande au Repository de trouver les élements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findby([], [], $this->limit, $offset);
        // envoyer les éléments
        return $data;
    }
    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entity sur laquelle nous devons paginer.
             Utiliser la méthode setEntityClass() de votre objet PaginationService !");
        }
    
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total / $this->limit);
        return $pages;
    }
    public function setPage($page)
    {
        $this->currentPage = $page;
        return $this;
    }
    public function getPage()
    {
        return $this->currentPage;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function getLimit()
    {
        return $this->limit;
    }
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
