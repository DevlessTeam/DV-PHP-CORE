<?php

namespace App\Helpers;

use App\User;
use Validator;


trait messageStack
{
    /**
     * application error heap.
     *
     * @var type
     */
    public static $MESSAGE_HEAP =
    [
        //JSON HEAP
        400 => 'Sorry something went wrong with payload(check json format)',
        //SCHEMA HEAP
        500 => 'first schema error',
        // error code for custom messages
        600 => 'Data type does not exist',
        601 => 'Reference column column name does not exist',
        602 => 'Database schema could not be created',
        603 => 'Table could not be created',
        604 => 'Service  does not exist or is not active',
        605 => 'No such resource try (rpc db view or schema)',
        606 => 'Created table successfully',
        607 => 'Could not find the right DB method',
        608 => 'Request method not supported',
        609 => 'Data has been added to table successfully',
        610 => 'Query parameter does not exist',
        611 => 'Table name is not set',
        612 => 'Query parameters not set',
        613 => 'Database has been deleted successfully',
        614 => 'Parameters `where` or `data`  not set',
        615 => 'Delete action not set ',
        616 => 'Caught unknown data type',
        617 => 'No such table belongs to the service',
        618 => 'Validator type does not exist',
        619 => 'Table was updated successfully',
        620 => 'Table could not be deleted ',
        621 => 'Asset file could not be found',
        622 => 'Token was updated successfully',
        623 => 'Token could not be updated',
        624 => 'Sorry this is not an open endpoint',
        625 => 'Got response successfully',
        626 => 'Saved script',
        627 => 'Sorry no such resource exists or resource is set to private (You can change this from the Privacy Tab or from the Rules section of that service)',
        628 => 'Sorry User is not authenticated, try logging in',
        629 => 'Sorry table could not be updated',
        630 => 'failed to push json to file',
        631 => 'Sorry access has been revoked (Please check App to see if your `Devless Token` match)',
        632 => 'There is something wrong with your input field',
        633 => 'Token has expired please try logging in again',
        634 => 'Seems the table does not exist',
        635 => 'Sorry to use offset you need to set size',
        636 => 'Data / table / field has been deleted',
        637 => 'Got RPC response successfully',
        638 => 'Sorry there is no such method or the method is set to private or protected',
        639 => 'Sorry RPC can only be processed over POST ',
        640 => 'Sorry there is no such related tables',
        641 => 'Something is wrong with your payload',
        642 => 'There is no such method in Rules Engine',
        643 => 'Sorry your account is not active',
        644 => 'Seems user already exist',
        700 => 'Internal system error',
    ];
}
