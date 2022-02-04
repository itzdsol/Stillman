<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // We need to provide default values to validate types
    'firebase' => [
        'database_url' => env('FIREBASE_DATABASE_URL', ''),
        'project_id' => env('FIREBASE_PROJECT_ID', 'stillmans-35611'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', 'dc8877db4ac347cb435ea81062fe2601cf6da0d4'),
        // replacement needed to get a multiline private key from .env 
        'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC28nV9qZL1Dr5O\nwPZKzcNhnBibFPfN931fMS9s5EJDhAkA3oSLOE4mQpQK7g4zkWLdbvAUFTPrVGzk\nZ5k85wd9GhDYoJ+cB7tCjII/aKI5Ilp1fssmQ8k1TRkve6cvPL2UP7hbX+Nq1IoE\na2Lu4asfaib4mAJ6aNk7xyLKzY2QwoWJTpmhgKh9Y08sdJK/3MDleBugCSWeCWQ2\nBlQATs90l3cdQ0m16u+bVEfM9J/a4Hac4cHNTMjiVJPfEOBQCncVLOcxKwEC45gp\n21NGaq1K6MKVRSb3cYDyJ6sLFfBFKRYG85m8zNvMzMmSr1SFc6mbButa3IB6ityD\nPfKi6Q+xAgMBAAECggEAAQOEOkAucwkUB2IwCzD4MfCFCpXANZLQ2q5UvbHHGmvF\nhzNBQN2jQZ8eH5bIAXbYn7/KknkdzeJGIMutKiZg+ktWCA+ql4xlOBfVhrq48v//\nrp9Kl2Mjq7xKLsmGRXxjIL24pj/FOUiSHWoN61ZyfD/tlUEpLfRdeperV7w+jYYj\nN3AbIl3g4SZO2wf/JyvF4m/Y+XdB1vw4IyNWCOau37dAVJSlaeRN1lWyXMGGKvYu\noSMj/i/g1qEuTO8HVGzyvsFx4JXoxhqyZBQsYJcl0wVYVhv4HNCsC0CQpR294h4T\nFtW7l18XPJyXvR4BR4bYh2nhmTpoU2u3vWC4HrH61QKBgQDdg4DfWoRnJyvLP50t\noHWsTgKhFbCi3YobKrRy0OI+nXjDUYpxkuqO26Eafv85r88J4TO8HD10bdsynXP4\nlvyvxWhMOH2YB9OpY1HJeYL9D0z8dU6WboB1MkBEWhvuVJ0ai43jZ6CjNznV1R6w\nZBGLZJiW4hgi5TO2dM2L+HSEVQKBgQDTbd9e75lIrwNhkRdqOJYGl2+wq6rPET30\n3m8MVjf1nNax8L+CkK2Y3sORprhwpY1KG1OOiW1Z8q46uI0s6PhectiROMQjSZ0Q\nzmMmW8d/VPkIYu3Fze5cV74F4XNmLQ+FHY3OFhfOoS9O4fHI3uy0rRDPjFMvJJbe\nOdJsyeVZ7QKBgQCN8FfBxvSUTItJX2fN9sWwNQSGR6m5ko+OPN0HvUVbrffV4AgA\nIo3eIGmo2rucMUVPJVjLLqVKV6JfWnkXT1h3IN1mCcSgiFNedN/RY+VFVmqUUm6b\nAg+aCSsZIUxIeTt56PZwQtSyAQ80L63MhTKgyULlW9bvdUKfpVSYXgn7SQKBgQCh\nHJSeL05LCLI24u1I0B3in/tIUUgzyhvAQM+2Qu0ZiI5BwgbtY3olh3rXvofFryHf\nWrttXmOpqzgBHyjVFCJPNoy4/NzZVvsNF3iOflmjOgkazugJV0dPrrzqlkXtkmDF\nOyQX6tXxavo1zg8E1nrhow+wWGn6/pLIfJ9QrvjKcQKBgEFlSqagAztxDQ8g1jAB\nG5bEALZ9o8c5hAN4+0GdhhNymYYuDsfM+Z1QQilMxjdCujJREz90mYS5JDuZQCNJ\nWXob20Q01GksdBYBOVsg0i+UHdkWJaYWUwD5OYHgGy6GZF/k/YBSVty9UOyuVN4M\nY1xOY38+4r82psgytJjO5sXv\n-----END PRIVATE KEY-----\n')),
        'client_email' => env('FIREBASE_CLIENT_EMAIL', 'firebase-adminsdk-w8rvq@stillmans-35611.iam.gserviceaccount.com'),
        'client_id' => env('FIREBASE_CLIENT_ID', '117610096690813965673'),
        'client_x509_cert_url' => env('FIREBASE_CLIENT_x509_CERT_URL', 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-w8rvq%40stillmans-35611.iam.gserviceaccount.com'),
    ]

];
