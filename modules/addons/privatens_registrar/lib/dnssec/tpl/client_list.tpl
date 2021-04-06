<!-- Tab -->
{include file='./tabs.tpl'}

<!-- Table -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">keyTag</th>
      <th scope="col">alg</th>
      <th scope="col">digestType</th>
      <th scope="col">digest</th>
      <th scope="col">action</th>
    </tr>
  </thead>
  <tbody>
     
    {foreach $data as $value}
        <tr>
          <th>{$value@index}</th>
          <td>{$value['keyTag']}</td>
          <td>{$value['alg']}</td>
          <td>{$value['digestType']}</td>
          <td>{$value['digest']}</td>
          <td>
              <button id="btn-delete" onClick=callSwall(event) class="btn btn-danger">
                  Hapus
                  
                  <form  method="POST" action="/index.php?m=privatens_dnssecmanager&domain={$domain}&action=delete">
                    <input type="hidden" value="{$domain}" name="domain">
                    <input type="hidden" value="{$value['keyTag']}" name="key_tag">
                    <input type="hidden" value="{$value['alg']}" name="alg">
                    <input type="hidden" value="{$value['digestType']}" name="digest_type">
                    <input type="hidden" value="{$value['digest']}" name="digest">
                  </form>
              </button>
          </td>
        </tr>
    {/foreach}
    
  </tbody>
</table>

{include file='./scripts.tpl'}