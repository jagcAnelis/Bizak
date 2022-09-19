{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="upload-products" class="lgtabcontent">
    <fieldset>
        <legend>
            <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=14" target="_blank">
                <span class="lglarge"><i class="icon-comments"></i>
                    {l s='Import product reviews' mod='lgcomments'}
                    <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                </span>
            </a>
        </legend>
        <div id="csv_uploader">
            <form method="post" action="" enctype="multipart/form-data">
                <br>
                <h3>
                    <label>
                        <i class="icon-exclamation-triangle"></i>
                        {l s='You must respect the following rules to upload the comments correctly:' mod='lgcomments'}
                    </label>
                </h3>
                <div>
                    <table style="width:900px;" class="table lgcenter" border="1" class="lgoverflow">
                        <tr>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} A</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} B</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} C</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} D</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} E</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} F</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} G</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} H</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} I</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} J</th>
                            <th style="text-align:center;" class="lgupper">{l s='Column' mod='lgcomments'} K</th>
                        </tr>
                        <tr>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Date' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column A of your CSV file, add the date of each comment.' mod='lgcomments'}
                                        {l s='Important: use the format dd/mm/yyyy (ex:15/04/2016)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Customer ID' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column B of your CSV file, add the ID of the customer' mod='lgcomments'}
                                        {l s='who wrote the comment (you can find it' mod='lgcomments'}
                                        <a href="index.php?tab=AdminCustomers&token={$tokenC|escape:'htmlall':'UTF-8'}" target="_blank" class="lgbold">
                                            {l s='on this page' mod='lgcomments'}
                                        </a>)
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Product ID' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column C of your CSV file, add the ID of the product' mod='lgcomments'}
                                        {l s='for which the comment was written (you can find it' mod='lgcomments'}
                                        <a href="index.php?tab=AdminProducts&token={$tokenPr|escape:'htmlall':'UTF-8'}" target="_blank" class="lgbold">
                                            {l s='on this page' mod='lgcomments'}
                                        </a>)
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Rating' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column D of your CSV file, add the rating on the product' mod='lgcomments'}
                                        {l s='(on a scale of /10)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Comment' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column E of your CSV file, add the comment' mod='lgcomments'}
                                        {l s='that has been written about the product' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Language ID' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column F of your CSV file, add the language ID' mod='lgcomments'}
                                        {l s='in which the comment was written (you can find it' mod='lgcomments'}
                                        <a href="index.php?tab=AdminLanguages&token={$tokenL|escape:'htmlall':'UTF-8'}" target="_blank" class="lgbold">
                                            {l s='on this page' mod='lgcomments'}
                                        </a>)
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip"><a href="#csv_uploader">{l s='Status' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column G of your CSV file, add the status of the comment' mod='lgcomments'}
                                        {l s='("1" for enabled and "0" for disabled)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Position' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column H of your CSV file, add the position of the comment' mod='lgcomments'}
                                        {l s='(compared to the other comments for the same product)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip">
                                    <a href="#csv_uploader">{l s='Title' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column I of your CSV file, add the title of the comment' mod='lgcomments'}
                                        {l s='(it will appear in bold before the comment)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip"><a href="#csv_uploader">{l s='Answer' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column J of your CSV file, add the answer to the comment' mod='lgcomments'}
                                        {l s='(optional, use "0" if there is no answer)' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                            <td class="lgpadding">
                                <span class="toolTip"><a href="#csv_uploader">{l s='Nick' mod='lgcomments'}</a>
                                    <span class="tooltipDesc">
                                        {l s='In the column K of your CSV file, add the nick for the user' mod='lgcomments'}
                                        {l s='This will be the name shown on comment, not customer name' mod='lgcomments'}
                                    </span>
                                </span>
                            </td>
                        </tr>
                    </table>
                    <div class="lgclear"></div>
                    <br>
                    <div class="alert alert-info">
                        -
                        {l s='Move your mouse over the table to get more information.' mod='lgcomments'}
                        <br>
                        {*</a>*}
                        -
                        <a href="{$module_path|escape:'htmlall':'UTF-8'}csv/example_products.csv">
                            {l s='Click here to download an example of CSV file' mod='lgcomments'}
                            {l s='(you can write your comments directly in it)' mod='lgcomments'}
                        </a>
                        <br>
                    </div>
                </div>
                <br>
                <br>
                <h3>
                    <span class="lgfloat">
                        <label>
                            <i class="icon-file-excel-o"></i>
                            {l s='Select your file' mod='lgcomments'}
                        </label>
                    </span>
                    <input type="file" name="csv1" id="csv1" class="btn btn-default lgfloat"><br>
                </h3>
                <div class="alert alert-info">
                    {l s='The file must be in.csv format and respect the structure indicated above (10 columns).' mod='lgcomments'}
                </div>
                <div class="lgclear"></div>
                <br>
                <br>
                <h3>
                    <span class="lgfloat">
                        <label>
                            <i class="icon-scissors"></i>
                            {l s='Indicate the separator of your CSV file (important)' mod='lgcomments'}
                        </label>
                    </span>
                    <select id="separator1" class="lgfloat fixed-width-xl" name="separator1">
                        <option value="1">
                            {l s='Semi-colon' mod='lgcomments'}
                        </option>
                        <option value="2">
                            {l s='Comma' mod='lgcomments'}
                        </option>
                    </select>
                </h3>
                <div class="alert alert-info">
                    -
                    {l s='Open your csv file with a text editor ("Notepad" for example)' mod='lgcomments'}
                    {l s='and check if the elements are separated with a semi-colon or comma.' mod='lgcomments'}<br>
                    -
                    {l s='If you use a comma separator, please remove all the comma from the titles,' mod='lgcomments'}
                    {l s='comments and answers in the CSV file to import the reviews correctly.' mod='lgcomments'}
                </div>
                <div class="lgclear"></div>
                <br>
                <br>
                <h3>
                    <span class="lgfloat">
                    <label>
                        <i class="icon-key"></i>
                        {l s='Character encoding' mod='lgcomments'}
                    </label>
                    </span>
                    <select id="encoding1" class="lgfloat fixed-width-xl" name="encoding1">
                        <option value="1">
                            {l s='Latin/roman alphabet' mod='lgcomments'}
                        </option>
                        <option value="2">
                            {l s='Other alphabets (East Europe, Cyrillic, Arabic, Greek, Chinese...)' mod='lgcomments'}
                        </option>
                    </select>
                </h3>
                <div class="alert alert-info">
                    {l s='Choose the alphabet used in your CSV file in order to import the file correctly' mod='lgcomments'}
                    {l s='and avoid character encoding problem.' mod='lgcomments'}
                </div>
                <br>
                <div class="lgclear"></div>
                <br>

                <div id="lgcomments_nick_options_container">
                    <h3>
                        <span class="lgfloat">
                            <label>
                                <i class="icon-key"></i>
                                {l s='Options for not available Nick' mod='lgcomments'}
                            </label>
                        </span>
                        <select id="LGCOMMENTS_NICK_OPTIONS" class="lgfloat fixed-width-xl" name="LGCOMMENTS_NICK_OPTIONS">
                            <option value="0">
                                {l s='Anonymous' mod='lgcomments'}
                            </option>
                            <option value="1">
                                {l s='Compound Fisrtname and Lastname. Ex for (Firstname: Jhnon, Lastname: Doe): J. Doe ' mod='lgcomments'}
                            </option>
                            <option value="2">
                                {l s='Force a name' mod='lgcomments'}
                            </option>
                        </select>
                    </h3>
                    <div class="alert alert-info">
                        {l s='If nick doesn\'t exists, you can decide what to do, leave it as anonymous comment, '  mod='lgcomments'}
                        {l s='compound it from Firstname and Lastname' mod='lgcomments'}
                        {l s='(For example if firstname = "Jhon" and Lastname is "Doe", the nick will be: "J. Doe"), ' mod='lgcomments'}
                        {l s='Or force a name that you want., ' mod='lgcomments'}
                    </div>
                    <div id="lgcomments_force_nick_container" style="display: none">
                        <span class="lgfloat">
                            <label>
                                <i class="icon-key"></i>
                                {l s='Nick' mod='lgcomments'}
                            </label>
                        </span>
                        <input type="text" id="LGCOMMENTS_FORCED_NICK" name="LGCOMMENTS_FORCED_NICK" class="lgfloat fixed-width-xl" />
                    </div>
                    <br>
                    <div class="lgclear"></div>
                    <br>

                </div>

                <label from="exportProductCSV"></label>
                <button class="button btn btn-default lgfloatright" type="submit" name="exportProductCSV">
                    <i class="process-icon-download"></i> {l s='Export all product comments' mod='lgcomments'}
                </button>
                <label from="productCSV"></label>
                <button class="button btn btn-default" type="submit" name="productCSV">
                    <i class="process-icon-import"></i> {l s='Import the product comments' mod='lgcomments'}
                </button>
            </form>
        </div>
    </fieldset>
</div>
