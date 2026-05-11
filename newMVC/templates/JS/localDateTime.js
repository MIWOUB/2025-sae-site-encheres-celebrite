function formatLocalDateTime(scope = document) {
  const elements = scope.querySelectorAll("[data-local-datetime]");

  elements.forEach((element) => {
    const value = element.getAttribute("data-local-datetime");

    if (!value) {
      return;
    }

    const date = parseServerDateTime(value);

    if (Number.isNaN(date.getTime())) {
      return;
    }

    element.textContent = new Intl.DateTimeFormat(undefined, {
      dateStyle: "medium",
      timeStyle: "short",
    }).format(date);
  });
}

function parseServerDateTime(value) {
  if (!value) {
    return new Date(NaN);
  }

  if (/[zZ]$|[+-]\d{2}:?\d{2}$/.test(value)) {
    return new Date(value);
  }

  const match = value.match(
    /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?$/,
  );

  if (!match) {
    return new Date(value);
  }

  const year = Number(match[1]);
  const month = Number(match[2]) - 1;
  const day = Number(match[3]);
  const hour = Number(match[4] ?? 0);
  const minute = Number(match[5] ?? 0);
  const second = Number(match[6] ?? 0);

  const desiredUtc = Date.UTC(year, month, day, hour, minute, second);
  let candidateUtc = desiredUtc;

  for (let i = 0; i < 2; i++) {
    const parts = new Intl.DateTimeFormat("en-GB", {
      timeZone: "Europe/Paris",
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hourCycle: "h23",
    }).formatToParts(new Date(candidateUtc));

    const lookup = Object.fromEntries(
      parts.map((part) => [part.type, part.value]),
    );
    const formattedUtc = Date.UTC(
      Number(lookup.year),
      Number(lookup.month) - 1,
      Number(lookup.day),
      Number(lookup.hour),
      Number(lookup.minute),
      Number(lookup.second),
    );

    const diffMinutes = Math.round((formattedUtc - desiredUtc) / 60000);

    if (diffMinutes === 0) {
      break;
    }

    candidateUtc -= diffMinutes * 60000;
  }

  return new Date(candidateUtc);
}

document.addEventListener("DOMContentLoaded", () => {
  formatLocalDateTime();
});
