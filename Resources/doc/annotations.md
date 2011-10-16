
## Defining an aspect

JMSAopBundle supports the declaring of several annotations within you Aspect to assist AOP.

    @AOP\Aspect
    @AOP\Pointcut
    @AOP\Before
    @AOP\AfterReturning
    @AOP\AfterThrowing

### Pointcut Annotations

    @AOP\Pointcut('execute(public Example\MyClass::method(..)')

### Putting it together

    use JMS\AopBundle\Configuration as AOP;
    use Symfony\Component\HttpKernel\Log\LoggerInterface;
    use JMS\DiExtraBundle\Annotation as DI;

    /**
     * @AOP\Aspect
     */
    class MyServiceLoggingAspect {

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
         * @AOP\Pointcut("execute(public MyService::*(..))");
         */
        public function trace() {}

        /**
         * @aop\Before("trace()");
         */
        public function beforeTrace(MethodInterceptorInterface $interceptor) {
            // $this->logger->info ...
        }
    }

