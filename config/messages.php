<?php

##----Login ---##
define('INACTIVE_USER', 'Your account is blocked.');
define('INVALID_USER_PASS', 'Invalid username or password.');
define('DELETED_USER', 'Your account is deleted by admin.');

##----Forgot Password---##
define('USER_NOT_FOUND','This email not found in database.');
define('SENT_FORGOT_MAIL_SUCCESS','Please check your mail to reset password. ');
define('SORRY_MSG','Sorry, something went wrong.');
define('EXP_TIME','Sorry, your token is expired.<br/>Please enter your email again to reset password');
define('PASS_RESET_SUCC','Password reset successfully.');

##-----Property Types---##
define('INVALID_NAME', 'Property type name can only contain alphanumeric characters ,brackets(),dot(.),comma(,),colon(:),underscore(_) ,ampersand(&),and hyphen(-).');
define('PROPTYPE_DEL_SUCC', 'Property type deleted successfully.');
define('PROPTYPE_DEL_ERR', 'An error occured while deleting Property type.');
define('PROPTYPE_ADD_SUCC', 'Property type created successfully.');
define('PROPTYPE_ADD_ERR', 'An error occured while creating Property type.');
define('PROPTYPE_UPDATE_SUCC', 'Property type updated successfully.');
define('PROPTYPE_UPDATE_ERR', 'An error occured while updating Property type.');

##------Survey Template Categories----##
define('INVALID_CAT_NAME', 'Category name can only contain alphanumeric characters ,brackets(),dot(.),comma(,),colon(:),underscore(_) ,ampersand(&),and hyphen(-).');
define('CAT_ADD_SUCC', 'Category created successfully.');
define('CAT_ADD_ERR', 'An error occured while creating Category.');
define('CAT_UPDATE_SUCC', 'Category updated successfully.');
define('CAT_UPDATE_ERR', 'An error occured while updating Category.');
define('CAT_DEL_SUCC', 'Category deleted successfully.');
define('CAT_DEL_ERR', 'An error occured while deleting Category.');

##------ Email Template-------##
define('BODY_BLANK_ERR', 'Body cannot be blank.');
define('INVALID_EMAIL_NAME', 'Name can only contain alphabets ,ampersand(&),and hyphen(-).');
define('TEMPLATE_ADD_SUCC', 'Email template added successfully.');
define('TEMPLATE_ADD_ERR', 'An error occured while creating Email template.');
define('TEMPLATE_UPDATE_SUCC', 'Email template updated successfully.');
define('TEMPLATE_UPDATE_ERR', 'An error occured while updating Email template.');
define('TEMPLATE_DEL_SUCC', 'Email template deleted successfully.');
define('TEMPLATE_DEL_ERR', 'An error occured while deleting Email template.');

##------Edit user profile-----##
define('EDIT_USER_PROFILE','User information saved successfully.');
define('DELETE_USER_SUCCESS','User deleted successfully.');
define('CREATE_USER','Contributor added successfully.');

##------COMPANY_____________##
define('CREATE_COMPANY','Company added successfully.');
define('UPDATE_COMPANY','Company information updated successfully.');
define('DELETE_COMPANY','Company deleted successfully.');

##--------Nodes Management-----##
define('POSITION_CHANGED', 'Sequence changed successfully.');
define('POSITION_CHANGE_ERR', 'An error occured while changing Sequence.');
define('NODE_ADD_SUCC', 'Parent node added successfully.');
define('NODE_ADD_ERR', 'An error occured while adding Parent node.');
define('NODE_UPDATE_SUCC', 'Parent node updated successfully.');
define('NODE_UPDATE_ERR', 'An error occured while updating Parent node.');
define('NODE_PARAM_ERR', 'Please Provide Valid Property Type.');
define('NODE_DEL_SUCC', 'Node deleted successfully.');
define('NODE_DEL_ERR', 'An error occured while deleting Node.');
define('INVALID_NODE_NAME','Node name can only contain alphanumeric characters ,brackets(),dot(.),comma(,),colon(:),underscore(_) ,ampersand(&),slash(\),and hyphen(-).');
define('CHILD_NODE_ADD_SUCC', 'Child node added successfully.');
define('CHILD_NODE_ADD_ERR', 'An error occured while adding Child node.');
define('CHILD_NODE_UPDATE_SUCC', 'Child node updated successfully.');
define('CHILD_NODE_UPDATE_ERR', 'An error occured while updating Child node.');
define('NODE_ASSIGN_SUCCESS','Node(s) assign to contributor successfully.');

##------- Edit Admin Profile -----##
define('PROFILE_EDIT_SUCC', 'Profile updated successfully.');
define('PROFILE_EDIT_ERR', 'An error occured while updating profile.');

##------- Change Password ----##
define('PASSWORD_EDIT_SUCC', 'Password changed successfully.');
define('PASSWORD_EDIT_ERR','An error occured while changing password.');

##------- Change Username ----##
define('UNAME_EDIT_SUCC', 'Username changed successfully.');
define('UNAME_EDIT_ERR','An error occured while changing username.');
define('UNAME_EDIT_EXIST','This username already exist.');
define('UNAME_EDIT_BF','Username accept characters and numbers. At least 5 signs.');

##------ Notification Mail---##
define('NOTIFY_EMAIL', 'Notification Email');
define('EMAIL_SENT_SUCC','Email sent Successfully.');
define('EMAIL_SENT_ERR', 'An error occured while sending email.');
define('EMAIL_QUEUED_SUCC', 'Email queued Successfully.');
define('EMAIL_QUEUED_ERR', 'An error occured while queuing email.');
define('DEADLINE_SUCC', 'Deadline set successfully.');
define('DEADLINE_ERR', 'An error occured while setting deadline.');
define('SURVEY_CLOSE_SUCC','Survey closed successfully.');
define('SURVEY_CLOSE_ERR','An error occured while closing Survey.');

##---------user------##
define('CHANGE_EMAIL_SUCCESS','Email changed successfully.');

##------- survey -----##
define('SURVEY_ADD_SUCC', 'Survey added Successfully.');
define('SURVEY_ADD_ERR', 'An error occured while adding survey.');
define('SURVEY_UPDATE_SUCC', 'Survey updated Successfully.');
define('SURVEY_UPDATE_ERR', 'An error occured while updating survey.');
define('QUESTION_BLANK_ERR', 'Question cannot be blank.');
define('QUESTION_ADD_SUCC', 'Question added successfully.');
define('SURVEY_DEL_SUCC', 'Survey deleted successfully.');
define('SURVEY_DEL_ERR', 'An error occured while deleting survey.');
define('QUESTION_ADD_ERR', 'An error occured while adding question.');
define('QUESTION_UPDATE_SUCC', 'Question updated successfully.');
define('QUESTION_UPDATE_ERR', 'An error occured while updating question.');
define('QUEST_DEL_SUCC', 'Question deleted successfully.');
define('QUEST_DEL_ERR', 'An error occured while deleting question.');
define('NODECAT_ADD_SUCC','Node category added successfully.');
define('NODECAT_ADD_ERR', 'An error occured while adding Node category. ');
define('NODECAT_UPDATE_SUCC', 'Node category updated successfully.');
define('NODECAT_UPDATE_ERR', 'An error occured while updating Node category. ');
define('NODECAT_DEL_SUCC', 'Node category deleted successfully.');
define('NODECAT_DEL_ERR', 'An error occured while deleting Node category.');
define('DISTRIBUTE_SUCC', 'Survey(s) are queued successfully for distribution.');
define('DISTRIBUTE_ERR','An error occured while distributing survey(s).');

## Survey  Table##
define('COL_ADD_SUCC', 'New column added successfully.');
define('COL_ADD_ERR', 'An error occured while adding New column. ');
define('CAPTURE_SUCC', 'Capture level updated successfully.');
define('CAPTURE_ERR', 'An error occured while updating Capture level. ');
define('COL_DEL_SUCC', 'Data column deleted successfully.');
define('COL_DEL_ERR', 'An error occured while deleting Data column.');
define('COL_UPDATE_SUCC', 'Data column updated successfully.');
define('COL_UPDATE_ERR', 'An error occured while updating Data column.');
define('COL_EXC_SUCC', 'Column disabled successfully.');
define('COL_EXC_ERR', 'An error occured while disabling Column.');
define('COL_INC_SUCC', 'Column enabled successfully.');
define('COL_INC_ERR', 'An error occured while enabling Column.');
define('INCLUDE_ERR','An error occured while including/excluding node.');

## Survey Output table
define('OUTPUT_ADD_SUCC', 'Output table added successfully.');
define('OUTPUT_ADD_ERR', 'An error occured while adding Output table. ');
define('OUTPUT_UPDATE_SUCC', 'Output table updated successfully.');
define('OUTPUT_DEL_SUCC','Output table deleted successfully.');
define('OUTPUT_DEL_ERR', 'An error occured while deleting Output table.');
define('DATA_UPDATE_SUCC','Datafield updated successfully.');
define('DATA_UPDATE_ERR','An error occured while updating Datafield.');

## Change Password
define('MIN_PASSWORD_LEN', 'Password length should be atleast 8 characters.');
define('INVALID_CONFIRM_PASSWORD', 'New Password & Confirm Password does not match.');
define('INCORRECT_PASSWORD', 'Old Password is incorrect.');

## COmmon
define('UNAUTHORISED_ACCESS', 'You are not authorised to access this site.');

## Contributor Answer Section
define('ANS_SUB_SUCC','Your answer submitted successfully.');
define('ANS_SUB_ERR','An error occured while submitting answer.');
define('SURVEY_COMPLETE_ERR','An error occured while completing survey.');
define('COMMENT_ADD_SUCC','Comment added successfully.');
define('COMMENT_ADD_ERR','An error occured while adding comment.');
define('COMMENT_UPDATE_SUCC','Comment updated successfully.');
define('COMMENT_UPDATE_ERR','An error occured while updating comment.');

## Report Section
define('STAT_UPDATE_SUCC','Status updated successfully.');
define('STAT_UPDATE_ERR','An error occured while updating status.');
