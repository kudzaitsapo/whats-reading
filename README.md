# WhatsReading
---------------


### What is it?
 - A cool whatsapp based novel-sharing and reading platform.
 - Built for authors and readers.


### Why it?
- Covid-19 Quarantine, getting bored at home?
- Like reading novels?
- Bored without anything to do?


### How do I get started?
- #### For developers:
  -  Install `php` &amp; `mysql`
  -  Run `git clone ${this_repository}`
  -  After cloning, create an `.env` file with the following parameters:
     -  `APP_NAME`
     -  `APP_KEY`
     -  `DB_CONNECTION`
     -  `DB_HOST`
     -  `DB_PORT`
     -  `DB_DATABASE`
     -  `DB_USERNAME`
     -  `DB_PASSWORD`
     -  `TWILIO_SID`
     -  `TWILIO_AUTH_TOKEN`
     -  `TWILIO_WHATSAPP_NUMBER`

    - After creating the environment variables and saving them, run `php artisan serve` in the root folder.

- #### For normal people:
  - Add the number <b>+14155238886</b> to your contacts and send the message `join weak-uncle`. 
  - After sending the message, you'll have joined the sandbox environment. To register for <b>WhatsReading</b>, send the message `REG {FIRSTNAME} {SURNAME} {USERTYPE}` to the number, and your number will be registered. There are two types of users: Authors and Readers. An example is `REG ARTEMIS FOWL AUTHOR`, which registers Artemis Fowl as an author. Another example is `REG JOHN LEWIS READER`, which registers John Lewis as a reader.
  - For more on what the system is capable of doing, send the message `SHOW HELP`.
  - 
