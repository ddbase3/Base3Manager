# Base3Manager

Base3Framework plugin manager UI and standalone host integration.

## Manager layout and floating chatbot

`Base3\Base3Manager\Page\Index` renders the shared ClientStack launcher once in the
page shell, outside the AJAX-updated `#content` container. The launcher loads
`chatbot/default` through the `ManagerChatbot` output. Both the launcher and
this endpoint require an explicit ALLOW from the same XRM-backed
`globalchatbotlauncherfeature` authorization at every request. The endpoint
does not impose an independent, potentially conflicting local identity check;
authenticated membership and any explicitly configured audiences are decided
by the host authorization service. No separate launcher, agent, transport or permission system is
created in Base3Manager.

For right docking, the existing ClientStack launcher resizes `body`; Manager
CSS bounds the `#head` to the remaining viewport width, while the absolute `#content` and its scrolling
`#wrap` follow the body's reduced width. Manager JavaScript preserves the
ClientStack `base3-chatbot-docked` body class when navigating between modules.
Closing or changing to an overlay restores the original layout through
ClientStack's existing dock lifecycle.

The Manager content shell is a `div` rather than a surrounding form, so settings
and XRM editors may contain their own valid forms. The existing XRM save control
serializes descendant inputs explicitly. The photography module skin also
selects `#content` without assuming it is a `form`.

The installed `Base3Backend` plugin owns the shared `ITranslation` registration.
`Base3Manager` no longer registers a competing translation service. Existing
plugin-owned language sets remain in their respective plugins. The manager
requires `Base3Backend` for this binding.

## Configured display data

Manager tabs and subnavigation entries may declare `data` alongside `content`
in their `local/Module/*.json` files. If present, `Page\Content` resolves the
target through the existing `IDisplay` class map and invokes `setData()` once
before `getOutput()`. Without `data`, the existing `IOutput` lookup remains
unchanged. The configuration is trusted project-authored JSON, not request
input. The selected `tabalias` must still match the configured module tab.

The Manager owns only navigation and dispatch. Display behavior, settings
storage, server endpoints and authorization stay with existing plugins.
`ManageSettings` supplies static settings modules. The former embedded
`SettingsDisplay` under `ManageAi` is no longer used in Manager navigation.
The existing overview module remains the first module.
