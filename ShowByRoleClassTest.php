<?php
/**
 * Created by PhpStorm.
 * User: lveeckha
 * Date: 25/03/2019
 * Time: 10:19
 */


use uzgent\ShowByRolesClass\ShowByRolesParser;

require_once "ShowByRolesParser.php";


//Empty test.
$values = [];
$values["field_annotation"] = "Nothing";
assert(uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, []) == null);

//Found test.
$values = [];
$values["field_annotation"] = '@SHOW_BY_ROLES=["role1"]';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role1"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

//Role mismatch test.
$values = [];
$values["field_annotation"] = '@SHOW_BY_ROLES=["role1"]';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles) == null);

//2 annotations separated by newline.
$values = [];
$values["field_annotation"] = "@SHOW_BY_ROLES=[\"role2\"]\n@HIDDEN";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

//2 annotations separated by newline.
$values = [];
$values["field_annotation"] = "@SHOW_BY_ROLES=[\"role2\"]\r\n@READONLY";

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));




//2 annotations separated by newline and a space.
$values = [];
$values["field_annotation"] = '@SHOW_BY_ROLES=["role2"] \n@READONLY';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
//assert(uzgent\HideByRoleClass\HideByRoleParser::parseAnnotationRoleId($values, $projectRoles) == 12);

//2 annotations.
$values = [];
$values["field_annotation"] = '@HIDDEN @SHOW_BY_ROLES=["role2"]';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role2"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

//roles with a space.
$values = [];
$values["field_annotation"] = '@HIDDEN=2 @SHOW_BY_ROLES=["role with a space"]';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with a space"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

//roles with a space and annotation.
$values = [];
$values["field_annotation"] = '@HIDDEN=2 @SHOW_BY_ROLES=["role with a space"] @ABC';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with a space"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

//roles with a space and annotation.
$values = [];
$values["field_annotation"] = '@HIDDEN @SHOW_BY_ROLES=["role with = B"] @ABC';

$projectRoles = [];
$projectRoles[12] = ["role_name" => "role with = B"];
assert(in_array(12, uzgent\ShowByRolesClass\ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles)));

echo "\nAll tests have run.";