<?php

require "vendor/autoload.php";
require "config.php";

use PHPHtmlParser\Dom;

// Helper functions
function latestIdeaOnWeb(string $username): string {
    $dom = new Dom;
    $dom->loadFromUrl(userUrl($username));
    return $dom->find(ideaSymbolHtmlClass(), 1)->innerHtml();
}

function userUrl(string $user) {
    return "https://www.tradingview.com/u/{$user}/";
}

function ideaSymbolHtmlClass() {
    return ".tv-widget-idea__symbol";
}

function getLatestIdeaRecordedOfUser(string $user) {
    if(!file_exists(userFileName($user))) {
        return null;
    }

    return file_get_contents(userFileName($user));
}

function userFileName(string $user) {
    return "{$user}_User.txt";
}

function users(): array {
    return file("users.txt", FILE_IGNORE_NEW_LINES);
}

function checkLatestPublishedSymbolOfUsers(array $users) {
    foreach ($users as $user) {
        checkLatestPublishedSymbolOfUser($user);
    }
}

function checkLatestPublishedSymbolOfUser(string $user) {
    $latestIdeaRecorded = getLatestIdeaRecordedOfUser($user);

    $latestIdeaOnWeb = latestIdeaOnWeb($user);

    if(!$latestIdeaRecorded || ($latestIdeaRecorded != $latestIdeaOnWeb)) {
        alertNewIdea($user, $latestIdeaOnWeb);
    } else {
        echo "No need to do anything for {$user} user.\n";
    }
}

function alertNewIdea(string $user, string $symbol) {
    alert($user, $symbol);
    writeToFile($user, $symbol);
}

function alert(string $user, string $symbol) {
    $client = new Maknz\Slack\Client(SLACK_WEBHOOK_URL);
    $userUrl = userUrl($user);
    $client->send("Trader <{$userUrl}|{$user}> has published new idea on symbol {$symbol}.");
}

function writeToFile(string $user, string $symbol) {
    $handle = fopen(userFileName($user), 'w');
    fwrite($handle, $symbol);
    fclose($handle);
}

// Command start here
checkLatestPublishedSymbolOfUsers(users());