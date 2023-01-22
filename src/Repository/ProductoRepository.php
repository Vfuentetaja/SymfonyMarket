<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @extends ServiceEntityRepository<Producto>
 *
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    public function save(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function searchText($searchText): array
    {
        return $this->createQueryBuilder('p')
             ->where('p.nombre LIKE :name')
              //->andWhere('p.descripcion LIKE :val OR p.nombre LIKE :val')
              ->setParameter('name', '%'.$searchText.'%')
              //->orderBy('p.id', 'ASC')
              //->setMaxResults(10)
              ->getQuery()
              ->getResult();
    }

    public function paginacion($dql,$pagina,$elementosPorPagina)
    {
        $paginador = new Paginator($dql);
        $paginador->getQuery()
            ->setFirstResult($elementosPorPagina * ($pagina - 1))
            ->setMaxResults($elementosPorPagina);
        return  $paginador;
    }

    public function buscarProductosRegalos($pagina=1,$elementosPorPagina=6)
    {
        $query=$this->createQueryBuilder('p')
            ->where('p.categoria = :name')
            ->setParameter('name','Regalos')
            ->getQuery(); 
        return $this->paginacion($query,$pagina,$elementosPorPagina); //utilizamos la query que nos recupera todos los registros 
                        //para pasarsela a la funcion "paginacion" de arriba y devolver el resutlado de esa funcion "paginacion"
    }

    public function buscarProductosRopa($pagina=1,$elementosPorPagina=6)
    {
        $query=$this->createQueryBuilder('p')
            ->where('p.categoria = :name')
            ->setParameter('name','Ropa')
            ->getQuery(); 
        return $this->paginacion($query,$pagina,$elementosPorPagina); //utilizamos la query que nos recupera todos los registros 
                        //para pasarsela a la funcion "paginacion" de arriba y devolver el resutlado de esa funcion "paginacion"
    }

    public function buscarProductosDecoracion($pagina=1,$elementosPorPagina=6)
    {
        $query=$this->createQueryBuilder('p')
            ->where('p.categoria = :name')
            ->setParameter('name','Decoracion')
            ->getQuery(); 
        return $this->paginacion($query,$pagina,$elementosPorPagina); //utilizamos la query que nos recupera todos los registros 
                        //para pasarsela a la funcion "paginacion" de arriba y devolver el resutlado de esa funcion "paginacion"
    }

    public function buscarProductosFiguras($pagina=1,$elementosPorPagina=6)
    {
        $query=$this->createQueryBuilder('p')
            ->where('p.categoria = :name')
            ->setParameter('name','Figuras')
            ->getQuery(); 
        return $this->paginacion($query,$pagina,$elementosPorPagina); //utilizamos la query que nos recupera todos los registros 
                        //para pasarsela a la funcion "paginacion" de arriba y devolver el resutlado de esa funcion "paginacion"
    }
//    /**
//     * @return Producto[] Returns an array of Producto objects
//     */
//    public function findOneBySomeField($value): ?Producto
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
