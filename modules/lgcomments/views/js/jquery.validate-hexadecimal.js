/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

$.validator.addMethod( "hexadecimal", function( value, element ) {
    return this.optional( element ) || /^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test( value );
}, "Please enter a hexadecimal number." );