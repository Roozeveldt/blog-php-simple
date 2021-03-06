<?php

$signup_rules = [
    'registration-email' => function () {
        return validate_user_field('registration-email');
    },
    'registration-login' => function () {
        return validate_user_field('registration-login');
    },
    'registration-password' => function () {
        return validate_user_field('registration-password');
    },
    'registration-password-repeat' => function () {
        return validate_user_field('registration-password-repeat');
    },
];

$login_rules = [
    'login' => function () {
        return validate_login_field('login');
    },
    'password' => function () {
        return validate_login_field('password');
    },
];

$comment_rules = [
    'content' => function () {
        return validate_comment_field('content');
    },
    'post_id' => function () {
        return validate_comment_field('post_id');
    },
];

$message_rules = [
    'content' => function () {
        return validate_message_field('content');
    },
    'receiver_id' => function () {
        return validate_message_field('receiver_id');
    },
    'sender_id' => function () {
        return validate_message_field('sender_id');
    },
];

$signin_rules = [
    'email' => function () {
        return validate_signin_field('email');
    },
    'password' => function () {
        return validate_signin_field('password');
    },
];

$photo_post_rules = [
    'photo-heading' => function () {
        return validate_photo_field('photo-heading');
    },
    'photo-url' => function () {
        return validate_photo_field('photo-url');
    },
    'photo-tags' => function () {
        return validate_photo_field('photo-tags');
    },
];

$video_post_rules = [
    'video-heading' => function () {
        return validate_video_field('video-heading');
    },
    'video-url' => function () {
        return validate_video_field('video-url');
    },
    'video-tags' => function () {
        return validate_video_field('video-tags');
    },
];

$text_post_rules = [
    'text-heading' => function () {
        return validate_text_field('text-heading');
    },
    'text-content' => function () {
        return validate_text_field('text-content');
    },
    'text-tags' => function () {
        return validate_text_field('text-tags');
    },
];

$quote_post_rules = [
    'quote-heading' => function () {
        return validate_quote_field('quote-heading');
    },
    'quote-content' => function () {
        return validate_quote_field('quote-content');
    },
    'quote-author' => function () {
        return validate_quote_field('quote-author');
    },
    'quote-tags' => function () {
        return validate_quote_field('quote-tags');
    },
];

$link_post_rules = [
    'link-heading' => function () {
        return validate_link_field('link-heading');
    },
    'link-url' => function () {
        return validate_link_field('link-url');
    },
    'link-tags' => function () {
        return validate_link_field('link-tags');
    },
];
