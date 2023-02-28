import {
  Alert,
  AlertIcon,
  ButtonGroup,
  FormControl,
  FormLabel,
  Icon,
  IconButton,
  Input,
  InputGroup,
  InputRightElement,
  Link,
  SimpleGrid,
  Textarea,
  useDisclosure,
  VStack
} from "@chakra-ui/react";
import { InertiaLink } from "@inertiajs/inertia-react";
import { ActionButton } from "@ziswapp/components/action-button";
import { Card, CardGroup } from "@ziswapp/components/card";
import { DonationItemList } from "@ziswapp/components/donation-item-list";
import { DonorInfo } from "@ziswapp/components/donor-info";
import { ImageModal } from "@ziswapp/components/image-modal";
import { PageHeader } from "@ziswapp/components/page-header";
import { usePageProps } from "@ziswapp/hooks";
import { AppLayout } from "@ziswapp/layouts";
import { Donation } from "@ziswapp/types/donation";
import dayjs from "@ziswapp/utils/datetime";
import { Fragment, useMemo } from "react";
import { Edit, XOctagon, Printer } from "react-feather";
import { Eye as HiEye } from "react-feather";

type PageActionProps = {
  donation: Partial<Donation>;
};

const PageAction = ({ donation }: PageActionProps) => {
  const isCancel = useMemo(() => donation.status === "cancel", [donation]);

  return (
    <ButtonGroup variant="outline">
      {!donation.is_inisiatif_verified && (
        <Fragment>
          {isCancel === false && (
            <ActionButton
              permission="action.donation.edit"
              as={InertiaLink}
              href={`/donation/${donation.id}/edit`}
              leftIcon={<Icon as={Edit} />}
              variant="solid"
              colorScheme="yellow"
            >
              Edit
            </ActionButton>
          )}
          {isCancel === false && (
            <ActionButton
              permission="action.donation.cancel"
              as={InertiaLink}
              href={`/donation/${donation.id}/cancel`}
              leftIcon={<Icon as={XOctagon} />}
              variant="solid"
              colorScheme="red"
            >
              Batalkan
            </ActionButton>
          )}
        </Fragment>
      )}
      {isCancel === false && (
        <ActionButton
          as={Link}
          href={`/donation/${donation.id}/invoice`}
          leftIcon={<Icon as={Printer} />}
          variant="solid"
          isExternal
        >
          Invoice
        </ActionButton>
      )}
    </ButtonGroup>
  );
};

const ShowDonationPage = () => {
  const modal = useDisclosure();
  const { donor, donation, items } = usePageProps();

  const renderAction = useMemo(() => <PageAction donation={donation} />, [donation]);

  return (
    <AppLayout permission="action.donation.show">
      <PageHeader title="Donasi" subtitle="Detail transaksi donasi disini.">
        {renderAction}
      </PageHeader>
      {donation.is_inisiatif_verified && (
        <Card my={4}>
          <Alert status="warning" rounded="md">
            <AlertIcon />
            Mohon maaf, untuk saat ini anda tidak bisa mengubah donasi yang menggunakan Virtual
            Account, silahkan input ulang dan hubungi IZI untuk melakukan cancel transaksi yang
            lama.
          </Alert>
        </Card>
      )}
      <Card my={4}>
        <DonorInfo donor={donor} />
      </Card>
      <Card my={4}>
        <VStack p={4} spacing={4} align="stretch">
          <CardGroup
            w="full"
            title="Transaksi"
            description="Detail transaksi, nomor transaksi, tipe, status dan nominal"
          >
            <SimpleGrid columns={3} spacing={4}>
              <FormControl isReadOnly>
                <FormLabel htmlFor="branch">Cabang</FormLabel>
                <Input id="branch" value={donation?.branch?.name} />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="user">Marketing</FormLabel>
                <Input id="user" value={donation?.user?.name} />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="identification_number">Nomor transaksi</FormLabel>
                <Input id="identification_number" value={donation?.identification_number} />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="transaction_at">Tanggal transaksi</FormLabel>
                <Input
                  id="transaction_at"
                  value={dayjs(donation?.transaction_at).format("DD MMMM YYYY")}
                />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="status">Status</FormLabel>
                <Input id="status" value={donation?.status} />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="type">Tipe pembayaran</FormLabel>
                <Input id="type" value={donation?.type} />
              </FormControl>
              <FormControl isReadOnly>
                <FormLabel htmlFor="amount">Nominal</FormLabel>
                <Input
                  id="amount"
                  value={new Intl.NumberFormat("id-ID", { minimumFractionDigits: 0 }).format(
                    donation.amount
                  )}
                />
              </FormControl>
            </SimpleGrid>
            <FormControl py={4} isReadOnly>
              <FormLabel htmlFor="note">Catatan</FormLabel>
              <Textarea id="note" resize="none" value={donation?.note || "-"} />
            </FormControl>
          </CardGroup>
        </VStack>
      </Card>
      {donation.account && (
        <Card my={4}>
          <VStack p={4} spacing={4} align="stretch">
            <CardGroup
              w="full"
              title="Rekening"
              description="Rekening pembayaran dan bukti pembayaran"
            >
              <SimpleGrid columns={3} spacing={4}>
                <FormControl>
                  <FormLabel>Bank</FormLabel>
                  <Input readOnly value={donation?.account?.bank?.name || "-"} />
                </FormControl>
                <FormControl>
                  <FormLabel>Rekening</FormLabel>
                  <Input readOnly value={donation?.account?.number || "-"} />
                </FormControl>
                <FormControl>
                  <FormLabel>Atas nama</FormLabel>
                  <Input readOnly value={donation?.account?.name || "-"} />
                </FormControl>
                <FormControl>
                  <FormLabel>Bukti bayar</FormLabel>
                  <InputGroup>
                    <InputRightElement>
                      <IconButton
                        disabled={donation?.file_url === null}
                        variant="link"
                        aria-label="Lihat bukti bayar"
                        icon={<HiEye />}
                        colorScheme={donation?.file_url ? "sky" : "gray"}
                        onClick={modal.onOpen}
                      />
                    </InputRightElement>
                    <Input
                      readOnly
                      value={donation?.file_url ? "file-bukti-pembayaran.jpg" : "-"}
                    />
                    <ImageModal
                      src={donation.file_url}
                      isOpen={modal.isOpen}
                      onClose={modal.onClose}
                    />
                  </InputGroup>
                </FormControl>
              </SimpleGrid>
            </CardGroup>
          </VStack>
        </Card>
      )}
      <Card my={4}>
        <VStack p={4} spacing={4} align="stretch">
          <CardGroup
            w="full"
            title="Items"
            description="Alokasi transaksi, jenis dana dan program serta nominal"
          >
            <DonationItemList items={items} />
          </CardGroup>
        </VStack>
      </Card>
    </AppLayout>
  );
};

export default ShowDonationPage;
