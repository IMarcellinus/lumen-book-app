<?php

namespace Tests\Unit\App\Exceptions;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class HandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    /** @test **/
    public function it_responds_with_html_when_json_is_not_accepted()
    {
        // Mock the request to not accept JSON
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('wantsJson')->andReturn(false); // Define the return value
        $request->shouldReceive('expectsJson')->andReturn(false); // Define the return value for expectsJson()

        // Mock the exception
        $exception = new \Exception('Error!');

        // Create an instance of the Handler
        $handler = new Handler();

        // Call the render method
        $result = $handler->render($request, $exception);

        // Assert that the response is not a JsonResponse
        $this->assertNotInstanceOf(JsonResponse::class, $result);

        // Additional assertions for HTML content (optional):
        // $this->assertStringContainsString('Error!', $result); // If you expect the error message
        // $this->assertStringContainsString('<html>', $result); // If you want to assert HTML structure

        // Cleanup Mockery (optional)
        Mockery::close();
    }
    /** @test */
    public function it_responds_with_json_for_json_consumers()
    {
        // Create a partial mock for the Handler class
        $handler = $this->getMockBuilder(Handler::class)
            ->onlyMethods(['isDebugMode']) // Specify the method to mock
            ->getMock();
        $handler->expects($this->once())
            ->method('isDebugMode')
            ->willReturn(false);

        // Mock the request to accept JSON
        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->expects($this->once())
            ->method('wantsJson')
            ->willReturn(true);

        // Mock the exception
        $exception = new \Exception('Doh!');

        // Call the render method
        $result = $handler->render($request, $exception);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $result);

        // Access the response data as an array (assuming it's JSON)
        $data = json_decode($result->getContent(), true); // Decode as associative array

        // Assert presence of keys (assuming it's an associative array)
        $this->assertArrayHasKey('error', $data);
        $this->assertArrayHasKey('message', $data['error']);
        $this->assertArrayHasKey('status', $data['error']);

        // Assert values
        $this->assertEquals('Doh!', $data['error']['message']);
        $this->assertEquals(400, $data['error']['status']);
    }
    /** @test */
    public function it_provides_json_responses_for_http_exceptions()
    {
        // Create a partial mock for the Handler class
        $handler = $this->getMockBuilder(Handler::class)
            ->onlyMethods(['isDebugMode']) // Mock only the isDebugMode method
            ->getMock();
        $handler->expects($this->any())
            ->method('isDebugMode')
            ->willReturn(false);

        // Mock the request to accept JSON
        $request = $this->getMockBuilder(Request::class)
            ->getMock();
        $request->expects($this->any())
            ->method('wantsJson')
            ->willReturn(true);

        // Define examples of HTTP exceptions
        $examples = [
            [
                'mock' => NotFoundHttpException::class,
                'status' => 404,
                'message' => 'Not Found'
            ],
            [
                'mock' => AccessDeniedHttpException::class,
                'status' => 403,
                'message' => 'Forbidden'
            ]
        ];

        foreach ($examples as $example) {
            $exception = $this->getMockBuilder($example['mock'])
                ->onlyMethods(['getStatusCode']) // Mock only getStatusCode
                ->getMock();
            $exception->expects($this->any())
                ->method('getStatusCode')
                ->willReturn($example['status']);

            // Call the render method
            $result = $handler->render($request, $exception);

            // Assert the response
            $this->assertInstanceOf(JsonResponse::class, $result);
            $data = $result->getData();
            $this->assertEquals($example['status'], $result->getStatusCode());
            $this->assertEquals($example['message'], $data->error->message);
            $this->assertEquals($example['status'], $data->error->status);
        }
    }
}

