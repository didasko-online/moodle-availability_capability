M.availability_capability = M.availability_capability || {};

M.availability_capability.form = Y.Object(M.core_availability.plugin);

// Capabilities available for selection.
// ...@property capabilities.
// ...@type Array.
M.availability_capability.form.capabilities = null;

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {Array} capabilities Array of capability strings
 */
M.availability_capability.form.initInner = function(capabilities) {
    this.capabilities = capabilities;
};

/**
 * Create an HTML node from the condition data and setup listeners.
 *
 * @param {Object} json Condition data including state.
 * @returns {*} An HTML node.
 */
M.availability_capability.form.getNode = function(json) {
    // Generate a unique ID suffix for this instance
    var uid = 'cap_' + (new Date().getTime()) + '_' + Math.floor(Math.random() * 100000);

    var html =
        '<div class="availability_capability">' +
        '    <label for="capability_input_' + uid + '">' + M.util.get_string('choose', 'availability_capability') + '</label>' +
        '    <div class="availability_capability_tag_box" id="capability_tags_container_' + uid + '"></div>' +
        '    <div class="input-group mt-2">' +
        '        <input type="text" class="form-control" id="capability_input_' + uid + '" autocomplete="off" />' +
        '        <div class="input-group-append">' +
        '            <span class="input-group-text bg-white border-left-0">' +
        '                <i class="fa fa-search text-muted"></i>' +
        '            </span>' +
        '        </div>' +
        '    </div>' +
        '    <ul class="list-group position-absolute w-100 zindex-dropdown mt-1" id="capability_list_' + uid + '"' +
        '           style="max-height: 200px; overflow-y: auto;"></ul>' +
        '</div>';

    var node = Y.Node.create('<span>' + html + '</span>');

    node.setData('selectedCaps', []);
    node.setData('uid', uid);

    this.setupSearchUI(node, uid);

    if (json.capabilities) {
        node.setData('selectedCaps', []);
        json.capabilities.forEach(function(cap) {
            M.availability_capability.form.createCapabilityTag(node, cap, true);
        });
    }

    return node;
};

/**
 * Fill the existing state data.
 *
 * @param {Object} value State value.
 * @param {Y.Node} node Condition node.
 */
M.availability_capability.form.fillValue = function(value, node) {
    value.capabilities = node.getData('selectedCaps') || [];
};

/**
 * Get the existing state data from the node.
 *
 * @param {Y.Node} node Condition node.
 * @returns {{capabilities: (*|*[])}}
 */
M.availability_capability.form.getValue = function(node) {
    return {
        capabilities: node.getData('selectedCaps') || []
    };
};

/**
 * Add any error messages to the errors array.
 *
 * @param {Array} errors List of error strings.
 * @param {Y.Node} node Condition node.
 */
M.availability_capability.form.fillErrors = function(errors, node) {
    var selected = node.getData('selectedCaps') || [];
    if (selected.length === 0) {
        errors.push('availability_capability:error_nocaps');
    }
};

/**
 * Setup the search box and badges container to help users select capabilities.
 *
 * @param {Y.Node} node Condition node.
 * @param {String} uid A unique ID to prevent HTML ID collisions between multiple condition instances.
 */
M.availability_capability.form.setupSearchUI = function(node, uid) {
    var input = node.one('#capability_input_' + uid);
    var list = node.one('#capability_list_' + uid);

    // Handle input
    input.on('input', function(e) {
        var query = e.target.get('value').toLowerCase();
        list.setHTML('');

        if (!query) {
            return;
        }

        var results = this.capabilities.filter(function(cap) {
            return cap.toLowerCase().includes(query);
        });

        results.forEach(function(result) {
            var li = Y.Node.create('<li class="list-group-item list-group-item-action p-2 cursor-pointer">' + result + '</li>');
            li.on('click', function() {
                input.set('value', '');
                list.setHTML('');
                M.availability_capability.form.createCapabilityTag(node, result);
            });
            list.append(li);
        });
    }, this);
};

/**
 * Creates a tag element for a capability and attaches it to the tag container.
 * Also manages click-to-remove logic and updates the node's selectedCaps.
 *
 * @param {Y.Node} node - The root node of this plugin instance.
 * @param {String} capability - The capability string to add.
 * @param {Boolean} suppressUpdate - Do not trigger an update of the values. Used when first loading page.
 */
M.availability_capability.form.createCapabilityTag = function(node, capability, suppressUpdate) {
    var uid = node.getData('uid');
    var tagContainer = node.one('#capability_tags_container_' + uid);
    var selected = node.getData('selectedCaps');

    // Don't add duplicates.
    if (selected.includes(capability)) {
        return;
    }

    selected.push(capability);
    node.setData('selectedCaps', selected);

    var tag = Y.Node.create(
        '<span class="badge badge-primary mr-1 mb-1">' +
        capability +
        ' <a href="#" class="text-white ml-1" data-cap="' + capability + '">&times;</a></span>'
    );

    tag.one('a').on('click', function(e) {
        e.preventDefault();
        tag.remove();

        var updated = node.getData('selectedCaps').filter(function(cap) {
            return cap !== capability;
        });
        node.setData('selectedCaps', updated);

        // Only trigger update if not suppressed.
        if (!suppressUpdate && M.core_availability && M.core_availability.form &&
            typeof M.core_availability.form.update === 'function') {
            M.core_availability.form.update();
        }
    });

    tagContainer.append(tag);

    // Only trigger update if not suppressed.
    if (!suppressUpdate && M.core_availability && M.core_availability.form &&
        typeof M.core_availability.form.update === 'function') {
        M.core_availability.form.update();
    }
};
