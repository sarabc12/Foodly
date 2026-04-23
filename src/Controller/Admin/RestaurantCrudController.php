<?php

namespace App\Controller\Admin;

use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\RestaurantRepository;
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

class RestaurantCrudController extends AbstractCrudController
{

    private RestaurantRepository $restaurantRepository;
    private AdminSecurityService $securityService;

    public function __construct(RestaurantRepository $restaurantRepository, AdminSecurityService $securityService)
    {
        $this->restaurantRepository = $restaurantRepository;
        $this->securityService = $securityService;
    }

    public static function getEntityFqcn(): string
    {
        return Restaurant::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $user = $this->getUser();

        if(!$user instanceof User) {
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)->andWhere('1 = 0');
        }
        return $this->restaurantRepository->createByUserQueryBuilder($user);
    }

    public function renderEntityParameters(AdminContext $context): KeyValueStore{
        $entity = $context->getEntity()->getInstance();
        $user = $this->getUser();

        if ($entity instanceof Restaurant && $user instanceof User) {
            $this->securityService->checkRestaurantOwnership($entity, $user);
        }

        return parent::renderEntityParameters($context);
    }
}

