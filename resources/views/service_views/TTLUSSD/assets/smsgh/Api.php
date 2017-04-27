<?php

require 'CurlException.php';
require 'HttpStatusCode.php';
require 'BasicHttpClient.php';

require 'Enum.php';
require 'JsonHelper.php';

require 'ApiHost.php';
require 'BasicAuth.php';
require 'OAuth.php';
require 'CommonUtil.php';

require 'ApiList.php';

require 'AbstractApi.php';

require 'AccountContact.php';
require 'AccountProfile.php';
require 'Action.php';
require 'Campaign.php';
require 'Contact.php';
require 'ContactGroup.php';
require 'ContentLibrary.php';
require 'ContentFolder.php';
require 'ContentMedia.php';
require 'FileExtensionMimeTypeMapping.php';
require 'Invoice.php';
require 'Message.php';
require 'MediaInfo.php';
require 'MessageDirection.php';
require 'MessageResponse.php';
require 'MessageTemplate.php';
require 'MessageType.php';
require 'MoKeyWord.php';
require 'NumberPlan.php';
require 'NumberPlanItem.php';
require 'Sender.php';
require 'Service.php';
require 'ServiceType.php';
require 'Setting.php';
require 'Tag.php';
require 'Ticket.php';
require 'TicketResponse.php';

require 'AccountApi.php';
require 'MessagingApi.php';
require 'ContactApi.php';
require 'SupportApi.php';
require 'ContentApi.php';


if (!function_exists('json_encode')) {
    trigger_error('SmsghApi requires the PHP JSON extension', E_USER_ERROR);
}
