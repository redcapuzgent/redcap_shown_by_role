<?php

namespace uzgent\ShowByRolesClass;

// Declare your module class, which must extend AbstractExternalModule
class ShowByRolesClass extends \ExternalModules\AbstractExternalModule {

    const annotation = "@SHOW_BY_ROLES";
    public function redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance)
    {
        //Get the current role.
        $userInitiator = \UserRights::getRightsAllUsers();
        $roleId = $userInitiator[USERID]["role_id"];

        $projectRoles = \UserRights::getRoles();
        $fieldsToHide = [];
        $metadata = $this->getMetadata($project_id);
        foreach($metadata as $fieldname => $values)
        {
            $foundUserRoleIds = $this->parseAnnotationRoleIds($values, $projectRoles);

            if ($foundUserRoleIds != null)
            {
                if (!in_array($roleId, $foundUserRoleIds))
                {
                    $fieldsToHide[]= $fieldname;
                }
            }
        }

        $this->printJavaScriptHiders($fieldsToHide);


    }

    /**
     * Gets the roles from ["role1", "role2"]
     * @param $elements
     * @param $projectRoles
     * @return int[]
     */
    public function getUserRoleIds($elements, $projectRoles)
    {
        $annotationRolesJson = $elements[1];
        $jsonObject = json_decode( $annotationRolesJson);
        if ($jsonObject == null)
        {
            echo "<div class=\"alert alert-danger\" role=\"alert\">Module config problem: The annotation user roles aren't in json format. Please revisit your roles.</div>";
            return [];
        }
        $foundUserRoleIds = [];
        foreach($jsonObject as $jsonRoleName) {
            $didFindUserRoleId = false;
            foreach ($projectRoles as $projectRoleId => $projectRoleDetails) {
                if ($projectRoleDetails["role_name"] == $jsonRoleName) {
                    $foundUserRoleIds []= $projectRoleId;
                    $didFindUserRoleId = true;
                }
            }
            if ($didFindUserRoleId == false) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">Module config problem: Role $jsonRoleName was found as an annotation but couldn't be found in your project. Please review your spelling and that the role exists in your project.</div>";
            }
        }
        return $foundUserRoleIds;
    }

    /**
     * @param $values
     * @param $projectRoles
     * @return string[]|null returns null if there is no annotation for this field.
     */
    public function parseAnnotationRoleIds($values,$projectRoles)
    {
        $currentAnnotation = $values["field_annotation"];
        if (strpos($currentAnnotation, self::annotation) != FALSE) {
            $elements = explode("=", $currentAnnotation);
            $foundUserRoleIds = $this->getUserRoleIds($elements, $projectRoles);
        } else {
            return null;
        }
        return $foundUserRoleIds;
    }

    /**
     * @param $fieldsToHide
     */
    public function printJavaScriptHiders($fieldsToHide)
    {
        echo "<script>";
        include "hideaway.js";

        foreach ($fieldsToHide as $fieldToHide) {
            echo 'UZG_hideaway.hideIt("' . $fieldToHide . '")';
        }

        echo "</script>";
    }

}
