<!-- Tab -->
{include file='./tabs.tpl'}

<form action="./index.php?m=privatens_registrar&page=dnssecmanager&domain={$domain}&action=insert" method="POST" >
    <input type="hidden" name="domain" value={$domain}>
  
    <div class="form-group">
        <label for="key_tag">Key Tag</label>
        <input type="text" class="form-control" id="key_tag" name="key_tag">
    </div>
  
    <div class="form-group">
        <label for="alg">Algoritma</label>
        <input type="text" class="form-control" id="alg" name="alg">
    </div>
    
    <div class="form-group">
        <label for="digest_type">Digest Type</label>
        <input type="text" class="form-control" id="digest_type" name="digest_type">
        <p class="help-block">* Reference for digest type <a target="_blank" href="https://www.iana.org/assignments/ds-rr-types/ds-rr-types.xhtml"> here </a>.</p>
    </div>
    
    <div class="form-group">
        <label for="digest">Digest</label>
        <input type="text" class="form-control" id="digest" name="digest">
    </div>
    
    <!--<div class="form-group">
        <label for="pubKey">Public Key</label>
        <input type="text" class="form-control" id="pubKey" name="pubKey">
    </div>-->
  
    <button type="submit" class="btn btn-primary">Submit</button>

</form>


