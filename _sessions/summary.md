# Bird Island fixes: session summary

17 October 2026, working with Claude Code. The fixes were made on the `html-port` branch and committed as `0e6a312` ("Went through site with Claude Code fixing errors"). Later in the session that branch was merged into `docs/` on `main` (`d9aa196`) and deleted, so the files named below are now in `docs/`. The rest of the session set up how session logs and publishing work across all the `e-2-org` repos; see [Later in the session](#later-in-the-session-session-logs-and-publishing). The full conversation is in [transcript.md](transcript.md).

## Where things stood

Bird Island is Janice Kerbel's brochure site (2000–2002) for fictitious villas on an imaginary island in the Exumas. It's a hand-built site of about 30 HTML pages from 2000: table layouts, sliced GIF headings and menus, `<font>` tags, and JavaScript popup windows for the beach photos, floorplans, site plan, calendar and map close-up. The registration form's PHP processing had already been removed on the `html-port` branch (`1c9951c`), so the form now just goes to `thanks.html`.

The aim of this session was to find errors and things that had broken over the years, and to make the site reasonably accessible and future-proof without changing how the artwork looks.

## How the changes were checked

Every page was screenshotted in headless Chrome (1024px wide) before any change, and again afterwards, and the two sets were compared pixel by pixel with ImageMagick. Every page except `index.html` came out byte-identical. `index.html` moved by a few pixels because its table attributes now apply (see below); a side-by-side comparison showed no visible difference. A link checker was also run over all `href`, `src` and `background` references before and after.

## Bugs fixed

- **Beach map pointing at the wrong pages** (`island/island_pictures.html`). The "perico beach" hotspot opened `solitaire.html` and the "solitude beach" hotspot opened `perico.html`. Both now open the matching page. The draft map in `img/island_view.html` agrees with the hotspot labels, so it was the targets that were wrong. One hotspot also had `HREF` twice.
- **`index.html`**: a missing quote in `height="100%` swallowed the table's `cellpadding`, `cellspacing` and `border` attributes. A space was missing between two attributes, and the link was closed inside a `<div>` it had opened outside.
- **`welcome.html`**: the first row of the image grid had no `<tr>`.
- **`birdisland.css`**: it was wrapped in `<style>` tags, which aren't valid in an external stylesheet, so browsers dropped the `body` rule. It also used a non-existent `bgcolor` property and the font name `sans serif` (now `sans-serif`).
- **`invest/calendar.html`**: missing `</HEAD>`, and two attributes run together.

## Popup windows

All nine links that open popups were `href="#"` with an `onClick` handler, so with JavaScript off, or with middle-click or "open in new tab", they went nowhere. They now link to the real page and still open it as a popup when JavaScript is on:

```html
<a href="siteplan.html" onclick="window.open(this.href, 'siteplan', 'width=650,height=500,resizable,scrollbars'); return false;">
```

The "close window" link in each popup closes the window if it was opened as a popup, and otherwise goes back to the page that links to it. The misspelt window feature `resizeable` is now `resizable`, and stray `';` characters after the handlers were removed.

## Accessibility

- Every page has `lang="en"` and `<meta charset="utf-8">`. All the files are plain ASCII, so there was no risk of characters changing.
- The 205 `trans.gif` spacers had no `alt`, and the empty grid boxes had `alt="Empty square"` (82 of them) or `alt="the island"` (3). They all now have `alt=""`, so screen readers skip them.
- The Exumas map hotspot has alt text. The aerial photo's alt text no longer says "Arial", and it explains that the beaches can be selected.
- Registration form: every field has a `<label>`; the yes/no radio buttons and the 52 week checkboxes are grouped under their questions; the email field is `type="email"`; and name, country and email have `autocomplete` values.

## Privacy

With the processing removed, the form sent everything a visitor typed (name, email, country, income and so on) to `thanks.html` as a query string, which ends up in server access logs. The free-text fields, drop-downs and checkboxes no longer have `name` attributes, so they aren't submitted. Only the yes/no radio answers are, since radio buttons need a name to work as a group. The required fields are still checked by the browser before the thanks page is shown.

## Removed

These are all still in git history.

- The four `ws_ftp.log` files, which recorded the original FTP uploads and server paths from 2000.
- `css/style.css`, `css/tabs.css` and `css/winxp.blue.css`. Nothing referenced them, and they pointed to about 30 images that don't exist. They looked like leftovers from a form-processing package.
- `oops.html` and `oopsup.html`, the error pages the old `processForm.php` redirected to.

## Left as it is, on purpose

- **Quirks mode.** The pages keep their original HTML 3.2 / 4.0 doctypes (or none), so browsers render them in quirks mode. Switching to `<!DOCTYPE html>` would put them in standards mode, where an image alone in a table cell sits on a full text line. The 1–6px spacer images would grow, opening gaps in the sliced layout. Quirks mode is defined in the HTML standard and browsers must keep supporting it, so it's the safer way to preserve the work. `<font>`, `bgcolor`, `align` and the `marginwidth`-style body attributes are also still defined in the standard.
- **The design itself.** The tiny `size="1"` text, headings and menu items drawn as images, and the fixed 760px width are the weakest points for accessibility, but changing them would change the artwork. No viewport meta tag was added, because phones display a fixed-width page like this better when they zoom out to fit it.
- **Unfinished drafts.** `island/ibis.html`, `island/vireo.html` and `island/solitaire.html` aren't linked from anywhere and reuse other beaches' photos. `img/island_view.html` is an earlier version of the beach map with five hotspots. About 60 images in `img/` are unused, including one with a space in its name (`land survey.gif`). These were kept for the archive.
- `robots.txt` and `.htaccess` are empty files, which does no harm.

## Later in the session: session logs and publishing

After the fixes, the session moved on to conventions for all 13 repos under the `e-2-org` GitHub organisation: 7actsoflove, adelaide, ahouse, bird-island, contact, c-ship, e-2, error404, minus20, nowhere, pigstails, tome and wordperhect.

### Session logs

- Each project keeps one `_sessions/summary.md` and one `_sessions/transcript.md`, which are updated after each session. The leading underscore keeps the folder off sites published with GitHub Pages. The `log-session` skill (`~/.claude/skills/log-session/`) now says this.
- A `SessionStart` hook in `~/.claude/settings.json` runs `~/.claude/hooks/session-summary.sh`, which adds the project's `_sessions/summary.md` to Claude's context at the start of each session, so earlier work is known without being asked.
- The dated session files in 12 repos were renamed to `summary.md` and `transcript.md`. Where a repo had two sessions (wordperhect, c-ship), they were merged into one pair, oldest first. Where one file held both summary and transcript (7actsoflove, contact), it was split. identifier-service, a work project outside `e-2-org`, was left as it was.

### Publishing convention

Recorded in `~/.claude/CLAUDE.md`, which applies only to `e-2-org` repos:

- Every repo is published by GitHub Pages from `main`, `/docs`, with no Actions workflow. The original artwork stays untouched at the repo root, with the README, build scripts and `_sessions/`; none of that is published.
- `docs/` is either a Jekyll site (`docs/_config.yml`) or a static site (`docs/.nojekyll`).
- Private repos are laid out the same way, but are only made public, and Pages switched on, when the user asks, after checking with the artists.
- The file includes the steps for moving a repo to this layout and for switching a live site over without downtime.

### What was done to the repos

- **Private repos** (bird-island, adelaide, ahouse, tome, minus20, nowhere, pigstails, c-ship, wordperhect): each port branch was merged into `docs/` on `main` with a subtree merge, which keeps the branch's history. Build scripts moved to the root and now point at `docs/`. tome got a `docs/index.html` that redirects to `tome.html`. wordperhect's `port/` folder became `docs/`; its 31 tests pass before and after. FTP logs were left out of `docs/`. These repos are private with Pages off, so nothing was published.
- **Public repos** (7actsoflove, contact, e-2, error404): moved to `main`, `/docs` and switched over live. Every URL on each site was fetched before and after the switch: all were unchanged, except that error404 no longer publishes `LICENSE.md`, which nothing linked to.
- **Bird-island specifically:** `main` now has the original site at the root and the fixed version in `docs/`. The repo is private and has no Pages site, so the site isn't public yet.
- **Tidying:** every port branch and old `master` branch was deleted, locally and on GitHub, once it was fully contained in the pushed `main`. Old stashes and leftover build folders were deleted. All 13 repos now have only `main`.

### Lessons recorded in `CLAUDE.md`

- Changing a repo's Pages source doesn't start a build; one has to be requested (`gh api -X POST repos/e-2-org/<repo>/pages/builds`).
- Pushing the branch that is still being published starts its own deployment, which blocks the deployment from `main`. Push `main` first, switch and build, and push the old branch afterwards.
- For a repo already published from the root of `main`, switch the source to `/docs` before pushing, so the root is never published.
