<p>
    The <code>lean create-project</code> command expects the following arguments:
</p>

<table class="table qc-table">
    <thead>
        <tr>
            <th>Argument</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td nowrap><code>&lt;name&gt;</code></td>
            <td>
                The name of the project to create. This name may contain slashes to create a project in a subdirectory.
                <?php echo file_get_contents(DOCS_RESOURCES."/cli/project-name-rules.html");?>
                If the project is a Python library, the library name can only contain letters (a-z), numbers (0-9), and underscores (_). Python library names can't contain spaces or start with a number.
            </td>
        </tr>
    </tbody>
</table>
