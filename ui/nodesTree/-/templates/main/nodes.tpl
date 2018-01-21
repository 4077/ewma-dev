<!-- nodes -->
<div class="nodes" node_path="{NODE_PATH}" parent_id="{PARENT_ID}" node_view="{VIEW}">

    <!-- nodes/node -->
    <div class="node {CLASS}" hover="hover">
        <div class="buttons">
            <!-- nodes/node/type -->
            <div class="select_type_button {NAME} {SELECTED_CLASS}" hover="hover" title="{NAME}" type="{NAME}">
                <div class="circle {NAME} {HAS_DATA_CLASS}">{CONTENT}</div>
            </div>
            <!-- / -->

            <div class="cb"></div>
        </div>

        <div class="indent {INDENT_CLICKABLE_CLASS}" hover="hover" style="width: {INDENT_WIDTH}px">
            <div class="icon {EXPAND_ICON_CLASS}"></div>
        </div>

        <div class="name" hover="hover" style="margin-left: {NAME_MARGIN_LEFT}px">{NAME}</div>

        <div class="cb"></div>
    </div>
    <!-- / -->

    <!-- nodes/subnodes -->
    <div class="subnodes {HIDDEN_CLASS}">

        <!-- nodes/subnodes/subnode -->
        {CONTENT}
        <!-- / -->

    </div>
    <!-- / -->

</div>
<!-- / -->

