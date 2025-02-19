# Show by roles
> [!CAUTION]
> This module has been deprecated, as its functionality can be built using REDCap's built-in @IF action tag (introduced in REDCap 11.4.0) and user-role smart variables (introduced in REDCap 11.2.0).

## Purpose
This module allows you to show a field to users with a specific role.

## How to use the module
### Using the module
- Enable the module for your project.
- Create a field with this annotation:  
`@SHOW_BY_ROLES=["role1","role2"]`
- The roles that you enter must be the exact name of the role as defined in the user rights environment.
- If a mentioned role cannot be found in your project an error will be shown.

### Example
```
@SHOW_BY_ROLES=["statistics","admin"]
```
