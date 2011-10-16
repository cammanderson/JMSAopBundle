
## Defining an aspect

JMSAopBundle supports the declaring of several annotations within you Aspect to assist AOP.

    @AOP\Aspect
    @AOP\Pointcut
    @AOP\Around
    @AOP\Before
    @AOP\AfterReturning
    @AOP\AfterThrowing

### Pointcut Annotations

    @AOP\Pointcut('execution(public Example\MyClass::method(..)')

### Putting it together

Create an aspect that will trace all calls to our "MyService"

    use JMS\AopBundle\Configuration as AOP;
    use Symfony\Component\HttpKernel\Log\LoggerInterface;
    use JMS\DiExtraBundle\Annotation as DI;

    /**
     * @AOP\Aspect
     */
    class MyServiceTracingAspect
    {

        private $logger;

        /**
         * @DI\InjectParams
         * @param LoggerInterface $logger
         */
        public __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;
        }

        /**
         * @aop\Around("execution(public MyService::*(..))");
         */
        public function aroundApiCall(MethodInterceptorInterface $interceptor)
        {
            // Something at the start

            $val = $interceptor->proceed();

            // Something at the end

            return $val;
        }
    }

