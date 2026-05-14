<?php

/**
 * Dropbox Exception class
 * @author Ben Tadiar <ben@handcraftedbyben.co.uk>
 * @link https://github.com/benthedesigner/dropbox
 * @package Dropbox
 */
#[AllowDynamicProperties]
class WPTC_Dropbox_Exception extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_BadRequestException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_CurlException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_NotAcceptableException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_NotFoundException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_NotModifiedException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_UnsupportedMediaTypeException extends Exception {
}

#[AllowDynamicProperties]
class WPTC_Dropbox_TokenExpired extends Exception {
}
