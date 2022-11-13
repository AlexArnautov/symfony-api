Interpeter (NodeJS Part)
====
Requirements: latest nodejs version

goto /nodejs
run: bash test.sh
It will run unit tests

Becouse it data intensive application I did not use any external libs.

Persistence Layer
====

https://dbdiagram.io/d/6370f6e1c9abfc61117242f0
I did denormalization of sensor table to avoid retriving data directly from messages table.
sensor.last_seen_at
sensor.last_error
sensor.last_state
I assume that this columns shuld be populated on message insert.
sensors.stats (Aggregated data for last 3 days should be populated via crontask on daily basis)

"Your first task is to decide which tools and technologies should be used in order to
answer every use case stated above. Make sure to give enough justification as to why
you are using those technologies."
I assume to use RabbitMQ for collecting messages.
NodeJS interprener as a consumer (in case eith RabbitMQ we can add as many consumers as we need)
I used MySQL as a data storage.
Also we can use key-value storage Redis for caching aggregated data (sensor.stats) and use sensor.id as key.
To avoid retriving data from DB on traffic light hover on the map.


API
====

Go to /symfony folder
run: bash up.sh
It will up API server on localhost
Please finde attached PostMan collection with API calls examples

"Describe a solution that would allow you to get the updated status of each sensor in real
time. Give as much justification as possible on why you decided on this solution and not
another one."
I would use data denormalization. Each message will update 2 tables message and sensor
in message table will saved message itself. On sensor table date from last message (sensor.last_seen_at,
sensor.last_error, sensor.last_state). It can be implemented via DB trigger or in Application level. It double
cost of insert message in DB, but in this case we only need to check sensor table to getting last data.
If insert operation became to slow we can introduce AMQP solution (RabbitMQ) for getting messages very fast to the queue. From the queue we can consume messages with any capacity.

"Describe which technique you would use to limit access to the API."
Classic rate limit algoritms "Sliding window", "Fixed Window" I would use ready solution like
https://symfony.com/doc/current/rate_limiter.html

"Describe a solution that would allow third-parties to be able to build applications (either
web or mobile) based on the sensor data normally accessible to your users."
JWT authentification, based on Oauth2 spec with AcccessToken and RereshToken
https://oauth.net/2/
https://datatracker.ietf.org/doc/html/rfc6749#section-1.5
https://jwt.io/


Web Application
====

go to /vue folder
run: npm install
run: npm run serve
Application will be availabe on http://localhost:8080/
Application ritrives http://localhost/api/sensors endpoint