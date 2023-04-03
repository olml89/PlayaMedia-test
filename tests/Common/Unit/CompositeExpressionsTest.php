<?php declare(strict_types=1);

namespace Tests\Common\Unit;

use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\AndExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\NotExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CompositeExpressions\OrExpression;
use olml89\PlayaMedia\Common\Domain\Criteria\CriteriaBuilder;
use olml89\PlayaMedia\User\Domain\Specifications\IsActiveSpecification;
use olml89\PlayaMedia\User\Domain\Specifications\IsMemberSpecification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\GetsKernelClass;

final class CompositeExpressionsTest extends KernelTestCase
{
    use GetsKernelClass;

    private readonly CriteriaBuilder $criteriaBuilder;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $this->criteriaBuilder = self::getContainer()->get(CriteriaBuilder::class);
    }

    public function test_not_expression(): void
    {
        $isMemberSpecification = new IsMemberSpecification(true);
        $criteria = $this->criteriaBuilder->not($isMemberSpecification)->build();

        /** @var NotExpression $notExpression */
        $notExpression = $criteria->expression();

        $this->assertEquals($isMemberSpecification->expression(), $notExpression->clause());
    }

    public function test_and_expression(): void
    {
        $specifications = [
            $isMemberSpecification = new IsMemberSpecification(true),
            $isActiveSpecification = new IsActiveSpecification(false),
        ];
        $criteria = $this->criteriaBuilder->and(...$specifications)->build();

        /** @var AndExpression $andExpression */
        $andExpression = $criteria->expression();

        $this->assertSameSize($specifications, $andExpression->clauses());
        $this->assertEquals($isMemberSpecification->expression(), $andExpression->clauses()[0]);
        $this->assertEquals($isActiveSpecification->expression(), $andExpression->clauses()[1]);
    }

    public function test_or_expression(): void
    {
        $specifications = [
            $isMemberSpecification = new IsMemberSpecification(true),
            $isActiveSpecification = new IsActiveSpecification(false),
        ];
        $criteria = $this->criteriaBuilder->or(...$specifications)->build();

        /** @var OrExpression $orExpression */
        $orExpression = $criteria->expression();

        $this->assertSameSize($specifications, $orExpression->clauses());
        $this->assertEquals($isMemberSpecification->expression(), $orExpression->clauses()[0]);
        $this->assertEquals($isActiveSpecification->expression(), $orExpression->clauses()[1]);
    }
}


