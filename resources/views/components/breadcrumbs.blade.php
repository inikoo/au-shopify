<!--suppress JSUnresolvedVariable, ES6ConvertVarToLetConst -->
<script type="text/javascript">
    var AppBridge = window['app-bridge'];
    var actions = AppBridge.actions;
    var TitleBar = actions.TitleBar;
    var Button = actions.Button;
    var Redirect = actions.Redirect;
    var titleBarOptions = {
        title: '{{$title}}',
    };
    var myTitleBar = TitleBar.create(app, titleBarOptions);
</script>
