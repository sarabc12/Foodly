<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\User;
use App\Repository\MenuRepository;
use App\Service\AdminSecurityService;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_OWNER')]
class MenuCrudController extends AbstractCrudController
{
    private MenuRepository $menuRepository;
    private AdminSecurityService $securityService;

    public function __construct(MenuRepository $menuRepository, AdminSecurityService $securityService)
    {
        $this->menuRepository = $menuRepository;
        $this->securityService = $securityService;
    }

    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $user = $this->getUser();

        if($user instanceof User){
            $this->menuRepository->filterByUser($queryBuilder, $user);
        }
        return $queryBuilder;
    }

}
