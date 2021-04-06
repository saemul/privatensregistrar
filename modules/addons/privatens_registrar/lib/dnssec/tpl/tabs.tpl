{include file='./styles.tpl'}

<!-- Tab -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link {if $page eq 'list'} dnssec__active {/if}" href="/index.php?m=privatens_registrar&page=dnssecmanager&domain={$domain}">List DNSSec</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {if $page eq 'insert'} dnssec__active {/if}" href="/index.php?m=privatens_registrar&page=dnssecmanager&domain={$domain}&action=forminsert">Insert DNSSec</a>
  </li>
</ul>