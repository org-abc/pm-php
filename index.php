<?php

    switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
        case '/acceptRequest.php':
            require 'acceptRequest.php';
            break;
        case '/cancelReq.php':
            require 'cancelReq.php';
            break;
        case '/changeMechanicPassword.php':
            require 'changeMechanicPassword.php';
            break;
        case '/changeMechanicStatus.php':
            require 'changeMechanicStatus.php';
            break;
        case '/changeUserPassword.php':
            require 'changeUserPassword.php';
            break;
        case '/checkResponse.php':
            require 'checkResponse.php';
            break;
        case '/getDriverInfo.php':
            require 'getDriverInfo.php';
            break;
        case '/getHistory.php':
            require 'getHistory.php';
            break;
        case '/getMechanicHistory.php':
            require 'getMechanicHistory.php';
            break;
        case '/getMechanics.php':
            require 'getMechanics.php';
            break;
        case '/getRequests.php':
            require 'getRequests.php';
            break;
        case '/getUserInfo.php':
            require 'getUserInfo.php';
            break;
        case '/login.php':
            require 'login.php';
            break;
        case '/loginMechanic.php':
            require 'loginMechanic.php';
            break;
        case '/request.php':
            require 'request.php';
            break;
        case '/sendFeedback.php':
            require 'sendFeedback.php';
            break;
        case '/sendMechanicCode.php':
            require 'sendMechanicCode.php';
            break;
        case '/sendPushNotification.php':
            require 'sendPushNotification.php';
            break;
        case '/sendUserCode.php':
            require 'sendUserCode.php';
            break;
        case '/setRequestAsResolved.php':
            require 'setRequestAsResolved.php';
            break;
        case '/signUp.php':
            require 'signUp.php';
            break;
        case '/signUpMechanic.php':
            require 'signUpMechanic.php';
            break;
        case '/updateLoc.php':
            require 'updateLoc.php';
            break;
        case '/updateMechanicLoc.php':
            require 'updateMechanicLoc.php';
            break;
        case '/updateMechanicProfile.php':
            require 'updateMechanicProfile.php';
            break;
        case '/updateProfile.php':
            require 'updateProfile.php';
            break;
        case '/verifyUser.php':
            require 'verifyUser.php';
            break;
        default:
            break;
    }
?>
