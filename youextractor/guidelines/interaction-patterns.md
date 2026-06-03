# Input Flow

The URL input is the centerpiece of the app. It should always be the largest, most prominent input on screen — use the large size with an icon. Pair it immediately with the primary gradient CTA below or beside it, inside a glass-accent card to create a clear "action zone."

# Loading States

Extraction takes time (10-30 seconds). During loading:

- The submit button enters loading state (spinner replaces icon)
- The input becomes non-interactive but stays visible (user can see what they submitted)
- Use rotating status messages to maintain engagement ("Analyzing video...", "Extracting code...")

Never show a blank loading screen — always keep context visible.

# Result Cards

Extraction results should be interactive cards (hover lift + glow). Each result needs:

- Video title as heading
- Channel + duration as secondary text
- Tech stack badges (scannable at a glance)
- Primary action (Download ZIP) and secondary action (View Tutorial)

Results appear with a fade-in animation, not a hard cut.

# Button Pairing

Action groups follow a follow a clear hierarchy:

- Hero CTA: gradient + full-width
- Primary + secondary: gradient or glow + ghost side by side
- Toolbar actions: all ghost, same size
- Destructive: use error color only for irreversible actions (sign out is not destructive)

# Empty States

When the video library is empty or search returns nothing, show a centered message with a muted icon, brief text, and a single CTA to extract a new video. Keep it simple — don't over-illustrate.
