/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awarded by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 * @author    ReactionCode <info@reactioncode.com>
 * @copyright 2015-2020 Garamo Online L.T.D
 * @license   Commercial license
 */

(function() {
    'use strict';

    // Initialize all user events when DOM ready
    document.addEventListener('DOMContentLoaded', initCommonForm, false);

    function initCommonForm() {
        var formTabs;
        var inputsWithSelectInput;
        var inputsWithSelectMenu;

        formTabs = document.querySelector('#js-form-tabs');
        if (formTabs) {
            formTabs.addEventListener('click', toggleTabs, false);
        }

        inputsWithSelectInput = document.querySelectorAll('.js-inputwithselect-input');
        if (inputsWithSelectInput) {
            inputsWithSelectInput.forEach(function (inputWithSelectNode) {
                if (inputWithSelectNode.min || inputWithSelectNode.max) {
                    inputWithSelectNode.addEventListener('change', inputWithSelectInput, false);
                }
            });
        }

        inputsWithSelectMenu = document.querySelectorAll('.js-inputwithselect-menu');
        if (inputsWithSelectMenu) {
            inputsWithSelectMenu.forEach(function (inputWithSelectNode) {
                inputWithSelectNode.addEventListener('click', inputWithSelectMenu, false);
            });
        }
    }

    // toggle form tabs
    function toggleTabs(event) {
        var tabSelectors = ['.js-tab-item'];
        var target = delegateEvents(tabSelectors, event.target);

        var targetClassList;
        var tabsList;
        var tabsContent;

        if (event.button === 0 && target) {
            targetClassList = target.classList;
            if (targetClassList.contains('reachable') && !targetClassList.contains('active')) {
                tabsList = document.querySelectorAll('.js-tab-item');
                tabsContent = document.querySelectorAll('.js-tab-content');

                // handle tab column
                tabsList.forEach(function (tab) {
                    if (tab.id === target.id) {
                        tab.classList.add('active');
                    } else {
                        tab.classList.remove('active');
                    }
                });

                // handle form values
                tabsContent.forEach(function (content) {
                    if (content.id !== 'js-tab-content-' + target.id) {
                        content.classList.add('hidden');
                    } else {
                        content.classList.remove('hidden');
                    }
                });
            }
        }
    }

    // handle imputs with select options
    function inputWithSelectMenu(event) {
        var optionSelector = ['.js-inputwithselect-option'];
        var target = delegateEvents(optionSelector, event.target);
        var mainSelector = ['.input-group-btn'];
        var mainNode;
        var inputNode;
        var labelNode;

        if (event.button === 0 && target) {
            event.preventDefault();
            mainNode = delegateEvents(mainSelector, target);
            inputNode = mainNode.querySelector('input');
            inputNode.value = target.dataset.value;
            labelNode = mainNode.querySelector('.js-inputwithselect-label');
            labelNode.innerText = target.innerText.trim();
        }
    }

    // handle input limits
    function inputWithSelectInput(event) {
        var winnerInputNode = event.target;

        if (winnerInputNode.max) {
            if (Number(winnerInputNode.value) > Number(winnerInputNode.max)) {
                winnerInputNode.value = winnerInputNode.max;
            }
        }

        if (winnerInputNode.min) {
            if (Number(winnerInputNode.value) < Number(winnerInputNode.min)) {
                winnerInputNode.value = winnerInputNode.min;
            }
        }
    }

    // JS TOOLS - LIKE JQ CLOSEST
    function delegateEvents(selectors, target) {

        var matchMode;

        if (target) {
            // get available browser matches function
            matchMode = target.matches || target.webkitMatchesSelector || target.msMatchesSelector;

            // get function name (general browsers || iE9)
            matchMode = matchMode.name || /function\s+([\w\$]+)\s*\(/.exec(matchMode.toString());

            // on iE9 get the name value, empty value on anonymous fn
            if (typeof matchMode !== 'string') {
                matchMode = matchMode ? matchMode[1] : '';
            }

            // continue only if we get matches selector function
            if (matchMode) {
                while (target.parentNode !== null) {
                    if (target.nodeType === 1) {
                        // iterate all selectors
                        for (var i = 0; i < selectors.length; i++) {
                            // compare if node match with selector
                            if (target[matchMode](selectors[i])) {
                                // if match return target
                                return target;
                            }
                        }
                    }
                    // if no match or nodeType !== 1 go to parent
                    target = target.parentNode;
                }
            }
        }
    }
})();