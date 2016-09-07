<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application;

final class Services
{
    const KERNEL_SERVICE_LOCATOR = 'dumplie.shared_kernel.service.locator';

    const KERNEL_COMMAND_BUS = 'dumplie.shared_kernel.command.bus';
    const KERNEL_COMMAND_HANDLER_MAP = 'dumplie.shared_kernel.command.handler_map';
    const KERNEL_COMMAND_EXTENSION_REGISTRY = 'dumplie.shared_kernel.command.extension_registry';

    const KERNEL_METADATA_SCHEMA_BUILDER = 'dumplie.shared_kernel.metadata.schema.builder';
    const KERNEL_METADATA_HYDRATOR = 'dumplie.shared_kernel.metadata.hydrator';
    const KERNEL_METADATA_ACCESS_REGISTRY = 'dumplie.shared_kernel.metadata.access_registry';

    const KERNEL_TRANSACTION_FACTORY = 'dumplie.shared_kernel.transaction.factory';
}