/**
 * Classe que define o form de "Alarm"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnussolution/magnusbilling7/issues
 * =======================================
 * Magnusbilling.org <info@magnussolution.com>
 * 03/01/2021
 */
Ext.define('MBilling.view.alarm.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.alarmform',
    initComponent: function() {
        var me = this;
        me.items = [{
            xtype: 'combobox',
            name: 'type',
            fieldLabel: t('Type'),
            forceSelection: true,
            editable: false,
            value: '1',
            store: [
                ['1', t('ALOC')],
                ['2', t('ASR')],
                ['3', t('Calls per minute')],
                ['4', t('Consecutive number')],
                ['5', t('Online calls on same number')],
                ['6', t('Same number and CallerID')]
            ]
        }, {
            xtype: 'combobox',
            name: 'period',
            fieldLabel: t('Period'),
            forceSelection: true,
            editable: false,
            value: '3600',
            store: [
                ['3600', t('1 Hour')],
                ['7200', t('2 Hours')],
                ['43200', t('12 Hours')],
                ['86400', t('24 Hours')]
            ]
        }, {
            xtype: 'combobox',
            name: 'condition',
            fieldLabel: t('Condition'),
            forceSelection: true,
            editable: false,
            value: '1',
            store: [
                ['1', t('Bigger than')],
                ['2', t('Less than')]
            ]
        }, {
            name: 'amount',
            fieldLabel: t('Amount')
        }, {
            name: 'email',
            fieldLabel: t('Email')
        }, {
            xtype: 'booleancombo',
            name: 'status',
            fieldLabel: t('Status')
        }];
        me.callParent(arguments);
    }
});