<?php 

    /**
     * CorsMiddleware
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link   https://github.com/davidecesarano/embryo-cors  
     */

    namespace Embryo\CORS;
    
    use Embryo\Http\Factory\ResponseFactory;
    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    class CorsMiddleware implements MiddlewareInterface 
    {   
        /**
         * @var array $allowed_origins
         */
        private $allowed_origins = ['*'];
        
        /**
         * @var array $allowed_methods
         */
        private $allowed_methods = ['*'];
        
        /**
         * @var array $allowed_headers
         */
        private $allowed_headers = ['*'];
        
        /**
         * @var array $exposed_headers
         */
        private $exposed_headers = [];
        
        /**
         * @var int $max_age
         */
        private $max_age = 0;
        
        /**
         * @var bool $supports_credentials
         */
        private $supports_credentials = false;

        /**
         * Set Access-Control-Allow-Origin. 
         * 
         * @param array $origins 
         * @return self
         */
        public function setAllowedOrigins(array $origins): self
        {
            $this->allowed_origins = $origins;
            return $this;
        }

        /**
         * Set Access-Control-Allow-Methods. 
         * 
         * @param array $methods 
         * @return self
         */
        public function setAllowedMethods(array $methods): self 
        {
            $this->allowed_methods = $methods;
            return $this;
        }

        /**
         * Set Access-Control-Allow-Headers. 
         * 
         * @param array $headers 
         * @return self
         */
        public function setAllowedHeaders(array $headers): self
        {
            $this->allowed_headers = $headers;
            return $this;
        }

        /**
         * Set Access-Control-Expose-Headers. 
         * 
         * @param array $headers 
         * @return self
         */
        public function setExposedHeaders(array $headers): self
        {
            $this->exposed_headers = $headers;
            return $this;
        }

        /**
         * Set Access-Control-Max-Age. 
         * 
         * @param int $maxAge 
         * @return self
         */
        public function setMaxAge(int $maxAge): self
        {
            $this->max_age = $maxAge;
            return $this;
        }

         /**
         * Set Access-Control-Allow-Credentials. 
         * 
         * @param bool $support 
         * @return self
         */
        public function setSupportsCredentials(bool $support): self
        {
            $this->supports_credentials = $support;
            return $this;
        }

        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $response = $handler->handle($request);
            $origin   = $request->getHeaderLine('origin');
            $method   = $request->getMethod();
            
            if ($this->allowed_origins[0] !== '*') {
                if (!$origin || !in_array($origin, $this->allowed_origins)) {
                    return $response;
                }
            }
            
            $origin   = $this->allowed_origins[0] === '*' ? '*' : $origin;
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
            $response = $response->withHeader('Access-Control-Allow-Headers', implode(',', $this->allowed_headers));
            $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $this->allowed_methods));
            if (!empty($this->exposed_headers)) {
                $response = $response->withHeader('Access-Control-Expose-Headers', implode(',', $this->exposed_headers));
            }
            if ($this->max_age > 0) {
                $response = $response->withHeader('Access-Control-Max-Age', (string) $this->max_age);
            }
            if ($this->supports_credentials) {
                $response = $response->withHeader('Access-Control-Allow-Credentials', (string) $this->supports_credentials);
            }
            return $response;
        }
    }