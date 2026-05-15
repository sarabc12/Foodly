<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\User;
use App\Repository\MenuRepository;
use App\Service\AdminSecurityService;
use App\Service\MenuLayoutService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Override;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_OWNER')]
class MenuCrudController extends AbstractCrudController
{
    private MenuRepository $menuRepository;
    private AdminSecurityService $securityService;
    private MenuLayoutService $menuLayoutService;

    public function __construct(MenuRepository $menuRepository, AdminSecurityService $securityService, MenuLayoutService $menuLayoutService)
    {
        $this->menuRepository = $menuRepository;
        $this->securityService = $securityService;
        $this->menuLayoutService = $menuLayoutService;
        
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->overrideTemplate('crud/index', 'admin/menu/cards.html.twig');
    }

    #[Override]
    public function configureActions(Actions $actions): Actions
    {
        $viewPlates = Action::new('viewPlates', 'vedipiatti', 'fa fa-utensil')
            ->linkToUrl(function (Menu $menu){
                return $this->container->get(AdminUrlGenerator::class)
                    ->setController(PlateCrudController::class)
                    ->setAction(Action::INDEX)
                    ->set('menuId', $menu->getId())
                    ->generateUrl();
            });
            
            return $actions
                ->add(Crud::PAGE_INDEX, $viewPlates);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm()->hideOnIndex();
        yield TextField::new('name', 'Menu Title');
        yield TextField::new('type', 'Menu Type');
        yield TextEditorField::new('description', 'Menu Description');
        yield AssociationField::new('restaurant', 'Choose the restaurant')
            ->setRequired(true)
            ->setFormTypeOptions([
                'placeholder' => 'Select a Restaurant',
                'query_builder' => function () {
                    $user = $this->getUser();
                    if (!$user instanceof User) {
                        throw new \LogicException('User not authenticated');
                    }
                    return $this->securityService->getRestaurantForUser($user);
                },
            ])
            ->formatValue(function($value, $entity) {
                return $entity->getRestaurant()?->getName();
            });
    }

    #[Override]
    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        $user = $this->getUser();
        if($user instanceof User){
            $responseParameters->set('groupedMenus', $this->menuLayoutService->getMenuGroupedByRestaurant($user));
        }
        return $responseParameters;
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Menu) {
            parent::persistEntity($entityManager, $entityInstance);
            return;
        }

        if ($entityInstance->getRestaurant() === null) {
            $user = $this->getUser();
            if ($user instanceof User) {
                $restaurant = $this->securityService->getRestaurantForUser($user)
                    ->getQuery()
                    ->setMaxResults(1)
                    ->getOneOrNullResult();

                if ($restaurant) {
                    $entityInstance->setRestaurant($restaurant);
                }
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

}

    

