<?php

namespace Tests\App\Http\Response;

use Tests\TestCase;
use App\Http\Response\FractalResponse;
use Mockery;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract; // Tambahkan impor ini

class FractalResponseTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test **/
    public function testItCanBeInitialized()
    {
        $manager = Mockery::spy(Manager::class);
        $serializer = Mockery::mock(SerializerAbstract::class);

        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /** @test **/
    public function testItCanTransformAnItem()
    {
        // Transformer
        $transformer = Mockery::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = Mockery::mock('League\Fractal\Scope');
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn(['foo' => 'bar']);

        // Serializer
        $serializer = Mockery::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = Mockery::mock('League\Fractal\Manager');
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer);
        $this->assertIsArray(
            $subject->item(['foo' => 'bar'], $transformer)
        );
    }

    /** @test **/
    public function testItCanTransformACollection()
    {
        $data = [
            ['foo' => 'bar'],
            ['fizz' => 'buzz'],
        ];

        // Transformer
        $transformer = Mockery::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = Mockery::mock('League\Fractal\Scope');
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn($data);

        // Serializer
        $serializer = Mockery::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = Mockery::mock('League\Fractal\Manager');
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer);
        $this->assertIsArray(
            $subject->collection($data, $transformer)
        );
    }
}
