
const CWWYA_templateAddNewApi = `
<div class="api  ">
<label class="input-api">
<h3 class="name-api">Name</h3>
<input 
    type="text" 
    class="input-name-api" 
    name="api[newApiID][name]" 
    id="api[newApiID][name]"
    value=""
>
</label>
<label class="input-api">
<h3 class="url-api">Url</h3>
<input 
    type="text" 
    class="input-url-api" 
    name="api[newApiID][url]" 
    id="api[newApiID][url]"
    value=""
>
</label>
<label class="input-api">
<h3 class="token-api">Token</h3>
<input 
    type="text" 
    class="input-token-api" 
    name="api[newApiID][token]" 
    id="api[newApiID][token]"
    value=""
>
</label>
<div class="permissions">
    <label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][product_ready]" 
id="api[newApiID][permission][product_ready]" 
    >
<h3 class="permission-api">product_ready</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][product_create]" 
id="api[newApiID][permission][product_create]" 
    >
<h3 class="permission-api">product_create</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][product_update]" 
id="api[newApiID][permission][product_update]" 
    >
<h3 class="permission-api">product_update</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][product_delete]" 
id="api[newApiID][permission][product_delete]" 
    >
<h3 class="permission-api">product_delete</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][order_ready]" 
id="api[newApiID][permission][order_ready]" 
    >
<h3 class="permission-api">order_ready</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][order_create]" 
id="api[newApiID][permission][order_create]" 
    >
<h3 class="permission-api">order_create</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][order_update]" 
id="api[newApiID][permission][order_update]" 
    >
<h3 class="permission-api">order_update</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][order_delete]" 
id="api[newApiID][permission][order_delete]" 
    >
<h3 class="permission-api">order_delete</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][user_ready]" 
id="api[newApiID][permission][user_ready]" 
    >
<h3 class="permission-api">user_ready</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][user_create]" 
id="api[newApiID][permission][user_create]" 
    >
<h3 class="permission-api">user_create</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][user_update]" 
id="api[newApiID][permission][user_update]" 
    >
<h3 class="permission-api">user_update</h3>
</label>
<label class="input-api-permission">
<input 
type="checkbox" 
class="input-permission-api" 
name="api[newApiID][permission][user_delete]" 
id="api[newApiID][permission][user_delete]" 
    >
<h3 class="permission-api">user_delete</h3>
</label>
</div>
<div class="contentDelete">
<input type="submit" class="delete-api-submit" value="newApiID" name="delete-api" id="delete-api"/>
<button class="button delete delete-api">Delete</button>
</div>
</div>
`;


let CWWYA_nItemsApis = undefined

const CWWYA_onLoad_nItemsApis = () => {
  const ele = document.getElementById("page-config-CWWYA")
  const atr = ele.getAttribute("data-n-items-apis")
  if(atr && !CWWYA_nItemsApis){
    CWWYA_nItemsApis = parseInt(`${atr}`)
  }
}

const CWWYA_onAddNewApi = () => {
  CWWYA_onLoad_nItemsApis()
  
  const contentApis = document.getElementById("contentApis");
  contentApis.innerHTML += `
        ${CWWYA_templateAddNewApi.split("newApiID").join(CWWYA_nItemsApis)}
    `;
    CWWYA_nItemsApis++;
};

const CWWYA_onLoadAddNewApi = () => {
  const addNewApi = document.getElementById("addNewApi");
  addNewApi.addEventListener("click", (e) => {
    e.preventDefault();
    CWWYA_onAddNewApi();
  });
};

const CWWYA_onDeleteApi = (submit) => {
    if(confirm("Are you sure?")){
        submit.click();
    }
};

const CWWYA_onLoadDeleteApi = () => {
  const btnsDeleteSubmit = document.querySelectorAll(".delete-api-submit");
  const btnsDelete = document.querySelectorAll(".delete-api");
  btnsDelete.forEach((btn, i) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      CWWYA_onDeleteApi(btnsDeleteSubmit[i]);
    });
  });
};

const CWWYA_onLoadPageConfig = () => {
  CWWYA_onLoadAddNewApi();
  CWWYA_onLoadDeleteApi();
};

window.addEventListener("load", CWWYA_onLoadPageConfig);
