{extends file="layout.tpl"}

{block name=title}
  {$problem->name} - {"attachments"|_}
{/block}

{block name=content}
  <h3>{$problem->name}</h3>

  <div>
    <a href="problem.php?id={$problem->id}">{"back"|_}</a>
  </div>

  <div class="voffset3"></div>

  <form method="post" role="form">
    <div class="panel panel-default">
      <div class="panel-heading">{"attachments"|_} ({$data|count})</div>

      <table class="table">
        <thead>
          <tr>
            {if $massActions}
              <th>
                <input type="checkbox" id="master-checkbox">
              </th>
            {/if}
            <th>{"name"|_}</th>
            <th>{"size"|_}</th>
            <th>{"uploader"|_}</th>
            <th>{"date"|_}</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$data item=d}
            <tr>
              {if $massActions}
                <td>
                  <input type="checkbox"
                         name="attachmentIds[]"
                         value="{$d.a->id}"
                         {if !$d.selectable}disabled{/if}>
                </td>
              {/if}
              <td>
                <a href="file.php?problemName={$problem->name}&amp;fileName={$d.a->name}">
                  {$d.a->name}
                </a>
              </td>
              <td>{include "bits/fileSize.tpl" s=$d.a->size}</td>
              <td>{include "bits/userLink.tpl" u=$d.a->getUser()}</td>
              <td>{include "bits/dateTime.tpl" ts=$d.a->created}</td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>

    <button type="submit" class="btn btn-default" name="download">
      <i class="glyphicon glyphicon-download-alt"></i>
      {"download"|_}
    </button>
    {if $permAddDelete}
      <button type="submit" class="btn btn-default" name="delete">
        <i class="glyphicon glyphicon-remove"></i>
        {"delete"|_}
    {/if}
    </button>
  </form>

  {if $permAddDelete}
    <h3>{"Add attachments"|_}</h3>

    <form method="post" role="form" enctype="multipart/form-data">
      <div class="col-lg-6 col-sm-6 col-12">

        <div class="input-group">
          <span class="input-group-btn">
            <span class="btn btn-default btn-file">
              <i class="glyphicon glyphicon-folder-open"></i>
              &nbsp; {"browse..."|_}
              <input type="file" name="files[]" multiple data-max-size="100000000">
            </span>
          </span>
          <input type="text" class="form-control" readonly>
        </div>

      </div>

      <button type="submit" class="btn btn-default">
        <i class="glyphicon glyphicon-upload"></i>
        {"upload"|_}
      </button>
    </form>
  {/if}
{/block}
