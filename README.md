# Base3Manager

Base3Framework plugin manager UI and standalone host integration.

## Manager layout and floating chatbot

`Base3Manager\Page\Index` renders the shared ClientStack launcher once in the
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

## Settings integration

The existing `?scope=web&module=manageai&tab=administration` URL remains valid.
`ManageAiAdministrationContentControl` renders `SettingsDisplay`. A scoped
`history_url` value is handed to ClientStack's existing TabControlDisplay so
browser history records Manager URLs, not the internal AJAX content URL.
Other host integrations preserve their existing history behavior when no
`history_url` is supplied.

Deployment and validation steps: see `ManageAi/README.md` for the canonical
`chatbot/default` instance and private managed-file storage prerequisites.
