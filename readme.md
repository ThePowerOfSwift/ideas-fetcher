# Ideas-Fetcher
This is a tool to get new ideas from tradingview.com by alert to your Slack when the selected trader has published new ideas.

## Installation
1. Clone the project to your computer
2. Run `composer install` to install dependencies
3. Copy `users.txt.example` to `users.txt` and add as many traders as you want, split by empty line
4. Copy `config.php.example` to `config.php` and put your Slack incoming webhook inside the variable SLACK_WEBHOOK_URL
5. Setup cronjob to run `fetch.php` every minute.
6. Done :)

## Example
After setup and a script found that there is a new idea has been published, it will push the alert to Slack like this.

![Example](https://image.prntscr.com/image/jBxXLRmWQR2I-flBuTmooA.png "Example")

You can click on the trader's username to go directly to their idea page. 