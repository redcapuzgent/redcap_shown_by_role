<?php

namespace uzgent\ShowByRolesClass;

require_once ("ShowByRolesParser.php");
// Declare your module class, which must extend AbstractExternalModule
class ShowByRolesClass extends \ExternalModules\AbstractExternalModule {


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
            $foundUserRoleIds = ShowByRolesParser::parseAnnotationRoleIds($values, $projectRoles);

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
     * @param $fieldsToHide
     */
    public function printJavaScriptHiders($fieldsToHide)
    {
        echo "<script>";
        include "hideaway.js";
        echo "function hideAll() { ";
        foreach ($fieldsToHide as $fieldToHide) {
            echo 'UZG_hideaway.hideIt("' . $fieldToHide . '");';
        }
        echo "}";
        echo "hideAll();\n";
        echo "</script>";
    }

}
