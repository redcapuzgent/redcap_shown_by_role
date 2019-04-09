<?php


namespace uzgent\ShowByRolesClass;


class ShowByRolesParser
{
    const annotation = "@SHOW_BY_ROLES";

    /**
     * @param $values
     * @param $projectRoles
     * @return int[]|null returns null if there is no annotation for this field.
     */
    public static function parseAnnotationRoleIds($values, $projectRoles)
    {
        $rawAnnotation = $values["field_annotation"];
        $currentAnnotations = explode("\n", str_replace("\r", "", $rawAnnotation));
        foreach ($currentAnnotations as $currentAnnotation)
        {
            if (strpos($currentAnnotation, self::annotation) !== FALSE) {
                $currentAnnotContent = substr($currentAnnotation, strpos($currentAnnotation, self::annotation) + strlen(self::annotation) + 1);
                $annotationAtPos = strpos($currentAnnotContent, "@");
                if ($annotationAtPos !== FALSE) // slice off other annotations that come after.
                {
                    $currentAnnotContent = substr($currentAnnotContent, 0, $annotationAtPos - 1);
                }
                return self::getUserRoleIds($currentAnnotContent, $projectRoles);
            }
        }
        return null;
    }

    /**
     * Gets the roles from ["role1", "role2"]
     * @param $elements
     * @param $projectRoles
     * @return int[]
     */
    public static function getUserRoleIds($elements, $projectRoles)
    {
        $annotationRolesJson = $elements;
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

}