<?php

namespace Tests\Traits;

trait TestUnitRequests
{
    /**
     * The testable rules.
     *
     * @var array
     */
    private $rules;

    /**
     * The testable validator.
     *
     * @var object
     */
    private $validator;

    /**
     * Get the testable request.
     *
     * @return string
     */
    abstract protected function request(): string;

    /**
     * Set up operations
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $request = $this->request();
        $this->rules = (new $request())->rules();
        $this->validator = $this->app['validator'];
    }

    /**
     * Check a field and value against validation rule
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function validateField(string $field, mixed $value): bool
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        )->passes();
    }
}
