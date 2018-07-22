## Messaging API

### How to install 
- Clone the repository
- run `cp .env.example .env`. Update with correct db credentials
- run `composer install`
- run `php artisan migrate`
- setup cron(`crontab -e`) with the following settings `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`


### Usage

- API endpoint: `/api/messages`
- Method: `POST`
- Params:
    - `text` : The full message to be send.
    - `to_number`: Recipients phone number.


### Usage Details

- All the messages will be saved in MessageQueue model
- A cron will be executed which will emulate sending sms, one message every second
- The emulated summary is logged in the file `storage/logs/message.log`
