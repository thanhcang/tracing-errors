# Tracing Errors
1. Supports tracing errors and transaction for 
   + Sentry
   + Zipkin
2. Supports put log for : 
   1. Docker input
   2. Cloud-watch 
3. Configuration: 
   3.1 Default Logs:
     - Keeping initial configuration
   3.2 Cloud-watch
   - 'log.channel' => env('LOG_CHANNEL', 'default') ,
   - 'log.aws.group' => env('LOG_GROUP'),
   - 'log.aws.group.default' => env('LOG_GROUP_DEFAULT'),
   - 'log.aws.stream' => env('LOG_STREAM'),
   + Change LOG_CHANNEL to cloudwatch
   + Change LOG_GROUP
   + Change LOG_GROUP_DEFAULT
   + Change LOG_STREAM
   
   3.2 Sentry 
     - 'services.sentry' => [
       'dsn' => env('SENTRY_DSN')
     ]
       
    + change SENTRY_DSN
   
   3.3 Zipkin
   + 'zipkin' => [
         'host' => env('ZIPKIN_HOST', 'localhost'),
         'port' => env('ZIPKIN_PORT', 9411),
         'options' => [
         '128bit' => false,
         'max_tag_len' => 1048576,
         'request_timeout' => 5,
         ],
   ], --> change configs
   
   - 'tracing.excluded_paths' => [
         '/'
   ] --> add excluded paths
     

Let's rock.
   