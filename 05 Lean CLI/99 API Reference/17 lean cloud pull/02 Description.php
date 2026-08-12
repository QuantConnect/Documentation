<p>
    Pulls projects from QuantConnect to your local directory while preserving the directory structure of your projects on QuantConnect.
    The project's files, description, and parameters are pulled from the cloud.
    By default, all cloud projects are pulled from the organization that's linked to your current <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a>. If you provide a <code>--project</code> option, you only pull a single project from the cloud.
</p>

<p>
    Before pulling a cloud project, the CLI checks if the local directory with the same path already exists.
    If it does and the local directory is not linked to the cloud project (because of an earlier <code>lean cloud pull</code> or <code>lean cloud push</code>), the CLI skips pulling the cloud project and logs a descriptive warning message.
</p>

<p>
    If you have a local copy of a project when you pull it from the cloud, local files that don't exist in the cloud are not deleted, but the configuration values of your cloud project overwrite the <a href='/docs/v2/lean-cli/projects/configuration#02-Properties'>configuration values of the local version</a>. If you have renamed the project in the cloud, when you pull the project from the cloud, the local project is renamed to match the name of the cloud project.
</p>

<?php echo file_get_contents(DOCS_RESOURCES."/libraries/collaboration.html"); ?>
