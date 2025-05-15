/**
 * Format date for API (MySQL format)
 * @param {Date|string} date - Date to format
 * @returns {string|null} Formatted date string in MySQL format (YYYY-MM-DD HH:mm:ss)
 */
export const formatDate = (date) => {
  if (!date) return null;
  const d = new Date(date);
  if (isNaN(d.getTime())) return null;

  // Convert to UTC string in MySQL format
  return d.toISOString().slice(0, 19).replace('T', ' ');
};

/**
 * Format date for datetime-local input fields
 * @param {Date|string} date - Date to format
 * @returns {string} Formatted date string for datetime-local input (YYYY-MM-DDThh:mm)
 */
export const formatDateForInput = (date) => {
  if (!date) return '';
  const d = new Date(date);
  if (isNaN(d.getTime())) return '';

  // Convert to local datetime-local format
  const localDate = new Date(d.getTime() - (d.getTimezoneOffset() * 60000));
  return localDate.toISOString().slice(0, 16);
};

/**
 * Convert UTC time to local time
 * @param {Date|string} date - UTC date to convert
 * @returns {Date|null} Date object in local time
 */
export const utcToLocal = (date) => {
  if (!date) return null;
  const d = new Date(date);
  if (isNaN(d.getTime())) return null;
  return d; // Let FullCalendar handle the timezone conversion
};

/**
 * Convert local time to UTC
 * @param {Date|string} date - Local date to convert
 * @returns {Date|null} Date object in UTC
 */
export const localToUtc = (date) => {
  if (!date) return null;
  const d = new Date(date);
  if (isNaN(d.getTime())) return null;
  return d; // The formatDate function will handle UTC conversion
};

/**
 * Format date to display in UI
 * @param {Date|string} date - Date to format
 * @returns {string} Formatted date string for display
 */
export const formatDisplayDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  if (isNaN(d.getTime())) return '';

  return new Intl.DateTimeFormat('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  }).format(d);
};

/**
 * Check if a date is today
 * @param {Date|string} date - Date to check
 * @returns {boolean} True if date is today
 */
export const isToday = (date) => {
  const today = new Date();
  const d = new Date(date);
  return d.getDate() === today.getDate() &&
    d.getMonth() === today.getMonth() &&
    d.getFullYear() === today.getFullYear();
}; 