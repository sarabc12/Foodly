<?php

namespace App\Controller\Admin;

use App\Entity\Plate;
use App\Entity\User;
use App\Repository\PlateRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Override;

class PlateCrudController extends AbstractCrudController
{
    private PlateRepository $plateRepository;
    
    public function __construct(PlateRepository $plateRepository)
    {
        $this->plateRepository = $plateRepository;
    }


    public static function getEntityFqcn(): string
    {
        return Plate::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $user = $this->getUser();
    
        if($user instanceof User){
            $this->plateRepository->filterByUser($queryBuilder, $user);
        }
        return $queryBuilder;
    }
    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnIndex();
        yield TextField::new('name', 'Plate');
        yield TextField::new('price', 'Price');
        yield TextField::new('description', 'Description');
        yield BooleanField::new('is_vegan', 'Vegan');
    }
}
