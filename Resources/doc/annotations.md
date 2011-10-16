
## Defining an aspect

JMSAopBundle supports the declaring of pointcuts with annotations within you aspect.

### Pointcut Annotations

    @AOP\Pointcut('execute(public Example\MyClass::method(..)')

### Putting it together

    use JMS\AopBundle\Configuration as AOP;
    use Symfony\Component\HttpKernel\Log\LoggerInterface;
    use JMS\DiExtraBundle\Annotation as DI;

    /**
     * @AOP\Aspect
     */
    class MyServerLoggingAspect {

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
            // ...
        }
    }

